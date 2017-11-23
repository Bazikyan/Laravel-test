@extends('layouts.layout')

@section('content')
<div class="container col-sm-offset-2 col-sm-8" style="margin-top: 60px;">
    <div>
        <form class="form-inline" action="{{ route('show-reports') }}" method="get" style="">
            <div class="row">
                <div class="form-group col-sm-3">
                    <select class="form-control" name="project" id="project">
                        <option value="">All Projects</option>
                        @foreach($projectArr as $project)
                            <option value="{{ $project->id }}" {{ $project->id === Request::input('project')+0 ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(session('mode') == 'owner')
                <div class="form-group col-sm-3">
                    <select class="form-control" name="user" id="user">
                        <option value="">All Users</option>
                        @foreach($userArr as $user)
                            <option value="{{ $user->id }}" {{ $user->id === Request::input('user')+0 ? 'selected' : '' }}>{{ $user->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="form-group col-sm-3 {{ session('mode') == 'owner' ? '' : 'col-sm-offset-2' }} ">
                    <select class="form-control" name="group" id="group">
                        @foreach(\App\Services\ReportBuilderService::$timeArr as $time)
                            <option value="{{ $time }}" {{ $time === Request::input('group') ? 'selected' : '' }} >Group By {{ $time }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group text-right col-sm-3 {{ session('mode') == 'owner' ? '' : 'col-sm-offset-1' }}">
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>
    <br>

    <table class="table table-bordered" style="margin-top: 50px; ">
        <thead class="thead-dark">
        <tr>
            <th class="col-sm-4" scope="col">Project Name</th>
            {{--<th scope="col">Task ID</th>--}}
            <th class="col-sm-2" scope="col">Count Of Tasks</th>
            {{--<th scope="col">Employer Name</th>--}}
            <th class="col-sm-4" scope="col">Duration</th>
            <th class="col-sm-2" scope="col">Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reportArr as $report)
            <tr>
                <td>{{ $report->project_name }}</td>
                {{--                <td>{{ $report->task_id }}</td>--}}
                <td>{{ $report->count_tasks }}</td>
{{--                <td>{{ $report->firstname.' '.$report->lastname }}</td>--}}
                <td>{{ $report->sum_durations }}</td>
                @switch(Request::input('group'))

                    @case(\App\Services\ReportBuilderService::$timeArr['year'])
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('Y') }}</td>
                    @break

                    @case(\App\Services\ReportBuilderService::$timeArr['month'])
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('M - Y') }}</td>
                    @break

                    @case(\App\Services\ReportBuilderService::$timeArr['week'])
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('l - M - Y') }}</td>
                    @break

                    @case(\App\Services\ReportBuilderService::$timeArr['day'])
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('d - M - Y') }}</td>
                    @break

                    @default
                    <td>All time</td>

                @endswitch
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="clearfix"></div>
<div class="text-center">{{ $reportArr->appends(Request::all())->links() }}</div>

@endsection
