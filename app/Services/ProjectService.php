<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 12:02 PM
 */

namespace App\Services;

use App\Project;
use Illuminate\Database\Query\Builder;

/**
 * Class ProjectService
 * @package App\Services
 */
class ProjectService extends AbstractService
{
    /**
     * @param integer $user_id
     * @return Builder $projectBuilder
     */
    public static function getProjects ($user_id, $filters = [])
    {
        $projectBuilder = Project::query();
        $projectBuilder->select(['projects.*',
            'users.email', 'firstname', 'lastname'])
            ->leftJoin('tasks', 'projects.id', '=', 'tasks.project_id')
            ->join('users', 'projects.user_id', '=', 'users.id');
        $projectBuilder = self::filterByMode($user_id, $projectBuilder);

        if ($filters) {
            if ($filters['project_name']) {
                $projectBuilder->where('projects.name', 'LIKE', '%' . $filters['project_name'] . '%');
            }
            if ($filters['task_name']) {
                $projectBuilder->where('tasks.name', 'LIKE', '%' . $filters['task_name'] . '%');
            }
        }
        $projectBuilder->groupBy('projects.id');
        return $projectBuilder;
    }

    /**
     * @param Builder $projectBuilder
     * @param integer $user_id
     * @return Builder $projectBuilder
     */
    protected static function getOwnerProjects ($user_id, $projectBuilder)
    {
        $projectBuilder->where('projects.user_id', '=', $user_id);

        return $projectBuilder;
    }

    /**
     * @param Builder $projectBuilder
     * @param integer $user_id
     * @return Builder $projectBuilder
     */
    protected static function getEmployerProjects ($user_id, $projectBuilder)
    {
        $projectBuilder->where('tasks.user_id', '=', $user_id);

        return $projectBuilder;
    }

    /**
     * @param Builder $projectBuilder
     * @param integer $user_id
     * @return Builder $projectBuilder
     */
    protected static function filterByMode($user_id, $projectBuilder)
    {
        $mode = 'owner';
        if (session()->has('mode') && session('mode') == 'employer') {
            $mode = 'employer';
        }
        if ($mode == 'owner') {
            $projectBuilder = self::getOwnerProjects($user_id, $projectBuilder);
        }
        else {
            $projectBuilder = self::getEmployerProjects($user_id, $projectBuilder);
        }
        return $projectBuilder;
    }

    /**
     * @param integer $user_id
     * @param integer $project_id
     * @return Builder $projectBuilder
     */
    public static function getProjectById ($user_id, $project_id)
    {
        $projectBuilder = self::getProjects($user_id);
        $projectBuilder->where('projects.id', '=', $project_id);

        return $projectBuilder;
    }

    /**
     * @param integer $user_id
     * @param array $data
     */
    public static function saveOrEditProject ($user_id, $data)
    {
        if($data['id']) {
            $project = self::getProjectById($user_id, $data['id'])->firstOrFail();
        }
        else {
            $project = new Project;
        }
        $project->user_id = $user_id;
        $project->name = $data['name'];
        $project->status = Project::STATUS_ACTIVE;
        $project->save();
    }

}