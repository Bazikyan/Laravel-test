<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Services\ReportBuilderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ReportController
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showList(Request $request)
    {
        $user_id = Auth::id();
        $userArr = UserService::getUsers($user_id)->get();
        $reportBuilder = new ReportBuilderService($request, $user_id);
        $reportArr = $reportBuilder->getBaseReportQuery()->getGroupByStatement()->getOrderByQuery()->getReportBuilder()->paginate(20);
        $projectArr = ProjectService::getProjects($user_id)->get();
        return \View::make('reports', ['reportArr' => $reportArr, 'projectArr' => $projectArr, 'userArr' => $userArr]);
    }
}
