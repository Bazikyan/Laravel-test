<?php

namespace App\Http\Controllers;

use App\Http\Requests\My\SaveProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProjectController
 * @package App\Http\Controllers
 */
class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showList(Request $request)
    {
        $user_id = Auth::id();

        $filters = ['project_name' => $request->input('project'),
            'task_name' => $request->input('task')];
        $projectArr = ProjectService::getProjects($user_id, $filters)->paginate(20);
        return \View::make('projects', ['projectArr' => $projectArr]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function findProject ($id)
    {
        $user_id = Auth::id();
        $project = ProjectService::getProjectById($user_id, $id)->firstOrFail();

        return \Response::json(['project' => $project]);
    }

    /**
     * @param SaveTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveProject (SaveProjectRequest $request)
    {
        $user_id = Auth::id();
        $data = [
            'name' => $request->input('project_name'),
            'id' => $request->input('project_id')
        ];
        ProjectService::saveOrEditProject($user_id, $data);
        return \Response::json(['true' => true]);
    }
}
