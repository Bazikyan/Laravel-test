<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 2:28 PM
 */

namespace App\Services;


use App\Task;
use Illuminate\Database\Query\Builder;

/**
 * Class TaskService
 * @package App\Services
 */
class TaskService extends AbstractService
{
    /**
     * @param integer $user_id
     * @param integer $task_id
     * @return Builder $taskBuilder
     */
    public static function getTaskById ($user_id, $task_id)
    {
        $taskBuilder = self::getTasks($user_id);
        $taskBuilder->where('tasks.id', '=', $task_id);

        return $taskBuilder;
    }

    /**
     * @param integer $user_id
     * @param array $filters
     * @return Builder $taskBuilder
     */
    public static function getTasks ($user_id, $filters = [])
    {
        $taskBuilder = Task::query();
        $taskBuilder->select(['tasks.*', 'projects.name AS project_name', 'firstname', 'lastname'])
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'tasks.user_id', '=', 'users.id');
        $taskBuilder = self::filterByMode($user_id, $taskBuilder);

        if ($filters) {
            if ($filters['project_name']) {
                $taskBuilder->where('projects.name', 'LIKE', '%' . $filters['project_name'] . '%');
            }
            if ($filters['task_name']) {
                $taskBuilder->where('tasks.name', 'LIKE', '%' . $filters['task_name'] . '%');
            }
            if ($filters['user_id']) {
                $taskBuilder->where('tasks.user_id', '=', $filters['user_id']);
            }
        }
        return $taskBuilder;
    }

    /**
     * @param Builder $taskBuilder
     * @param integer $user_id
     * @return Builder $taskBuilder
     */
    protected static function getOwnerTasks ($user_id, $taskBuilder)
    {
        $taskBuilder->where('projects.user_id', '=', $user_id);

        return $taskBuilder;
    }

    /**
     * @param Builder $taskBuilder
     * @param integer $user_id
     * @return Builder $taskBuilder
     */
    protected static function getEmployerTasks ($user_id, $taskBuilder)
    {
        $taskBuilder->where('tasks.user_id', '=', $user_id);

        return $taskBuilder;
    }

    /**
     * @param integer $user_id
     * @param array $data
     */
    public static function saveOrEditTask ($user_id, $data)
    {
        if($data['id']) {
            $task = self::getTaskById($user_id, $data['id'])->firstOrFail();
        }
        else {
            $task = new Task;
        }
        $task->user_id = $data['user_id'];
        $task->project_id = $data['project_id'];
        $task->name = $data['name'];
        $task->save();
    }

    /**
     * @param Builder $taskBuilder
     * @param integer $user_id
     * @return Builder $taskBuilder
     */
    protected static function filterByMode ($user_id, $taskBuilder)
    {
        $mode = 'owner';
        if (session()->has('mode') && session('mode') == 'employer') {
            $mode = 'employer';
        }

        if ($mode == 'owner') {
            $taskBuilder = self::getOwnerTasks($user_id, $taskBuilder);
        }
        else {
            $taskBuilder = self::getEmployerTasks($user_id, $taskBuilder);
        }
        return $taskBuilder;
    }
}