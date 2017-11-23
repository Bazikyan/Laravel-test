<?php

namespace App\Http\Controllers;

use App\Http\Requests\My\SaveTaskRequest;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class TaskController
 * @package App\Http\Controllers
 */
class TaskController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showList(Request $request)
    {
        $user_id = Auth::id();
        $filters = ['project_name' => $request->input('project_name'),
            'task_name' => $request->input('task'),
            'user_id' => $request->input('user')
        ];

        $taskBuilder = TaskService::getTasks($user_id, $filters);
        $taskArr = $taskBuilder->paginate(20);
        $projectArr = ProjectService::getProjects($user_id)->get();
        $userArr = UserService::getUsers($user_id)->get();
        return \View::make('tasks', ['taskArr' => $taskArr, 'projectArr' => $projectArr, 'userArr' => $userArr]);
    }

    /**
     * @param SaveTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveTask(SaveTaskRequest $request)
    {
        $user_id = Auth::id();
        $data = ['user_id' => $request->input('user'),
            'project_id' => $request->input('project'),
            'name' => $request->input('task_name'),
            'id' => $request->input('task_id')
        ];
        TaskService::saveOrEditTask($user_id, $data);
        return \Response::json(['true' => true]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function findTask ($id)
    {
        $user_id = Auth::id();
        $task = TaskService::getTaskById($user_id, $id)->firstOrFail();
//        $task = Task::query()->find($id);

        return \Response::json(['task' => $task]);
    }
}
