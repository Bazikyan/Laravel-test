<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 6:00 AM
 */

namespace App\Services;

use App\Report;
use App\Services\ReportGroupByStatement\GroupByAll;
use App\Services\ReportGroupByStatement\GroupByDay;
use App\Services\ReportGroupByStatement\GroupByMonth;
use App\Services\ReportGroupByStatement\GroupByWeek;
use App\Services\ReportGroupByStatement\GroupByYear;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Mockery\Exception;

/**
 * Class ReportBuilderService
 * @package App\Services
 * @property Builder $report
 * @property integer $user_id
 * @property Request $request
 * @property array $timeArr
 */
class ReportBuilderService extends AbstractService
{
    public static $timeArr = ['all' => 'All', 'year' => 'Year', 'month' => 'Month', 'week' => 'Week', 'day' => 'Day'];

    protected $request;
    protected $user_id;
    protected $report;

    /**
     * ReportBuilderService constructor.
     * @param Request $request
     * @param integer $user_id
     */
    public function __construct(Request $request, $user_id)
    {

        if (!in_array($request->input('group'), self::$timeArr) && $request->input('group')) {
            throw new Exception('Group type must by given from \App\Services\ReportBuilderService::$timeArr array');
        }

        $this->user_id = $user_id;
        $this->request = $request;
        $this->report = Report::query();
    }

    /**
     * @return $this
     */
    protected function getBaseReportQuery()
    {
        $user_id = $this->user_id;

        $this->report->select([
            'reports.created_at AS date', 'users.firstname', 'users.lastname',
            'projects.name AS project_name',
            \DB::raw("IF( SUM(duration) < 60, CONCAT( SUM(duration), ' minute' ),
             IF (SUM(duration) % 60, CONCAT( FLOOR(SUM(duration) / 60), ' hour, ', SUM(duration) % 60, ' minute' ), CONCAT( FLOOR(SUM(duration) / 60), ' hour') ) ) AS sum_durations"),
            \DB::raw('COUNT(tasks.id) AS count_tasks'),
        ])
            ->join('tasks', 'reports.task_id', '=', 'tasks.id')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'tasks.user_id', '=', 'users.id');

        $this->report = self::filterByMode($this->report, $user_id);

        if ($this->request->input('project')) {
            $this->report->where('projects.id', '=', $this->request->input('project'));
        }
        if ($this->request->input('user')) {
            $this->report->where('tasks.user_id', '=', $this->request->input('user'));
        }

        $this->report->groupBy('projects.id');
        return $this;
    }

    /**
     * @return $this
     */
    public function getOrderByQuery()
    {
        $this->report->orderBy('projects.id')->orderBy('reports.created_at');
        return $this;
    }

    /**
     * @param string $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {

        switch ($this->request->input('group')) {
            case self::$timeArr['year']  : $class_name = GroupByYear::class; break;
            case self::$timeArr['month'] : $class_name = GroupByMonth::class; break;
            case self::$timeArr['week']  : $class_name = GroupByWeek::class; break;
            case self::$timeArr['day']   : $class_name = GroupByDay::class; break;
            default                      : $class_name = GroupByAll::class; break;
        }

        $childObj = new $class_name($this->request, $this->user_id);
        return call_user_func([$childObj, $name]);
    }

    /**
     * @return Builder
     */
    public function getReportBuilder()
    {
        return $this->report;
    }

    /**
     * @param Builder $reportBuilder
     * @param integer $user_id
     * @return Builder $reportBuilder
     */
    protected static function filterByMode($reportBuilder, $user_id)
    {
        $mode = 'owner';
        if (session()->has('mode') && session('mode') == 'employer') {
            $mode = 'employer';
        }

        if ($mode == 'owner') {
            $reportBuilder->where('projects.user_id', '=', $user_id);
        } elseif ($mode == 'employer') {
            $reportBuilder->where('tasks.user_id', '=', $user_id);
        }

        return $reportBuilder;
    }
}