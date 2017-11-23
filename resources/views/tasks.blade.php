@extends('layouts.layout')

@section('content')

<div class="container col-sm-8 col-sm-offset-2" style="margin-top: 60px;">


    <div class="">
        <form class="form-inline" method="GET" action="{{ route('show-tasks') }}">
            <div class="row">
                <div class="form-group col-sm-4">
                    <input id="project_name" type="text" class="form-control" name="project_name" placeholder="Project Name" value="{{ \Request::input('project_name') }}">
                </div>

                <div class="form-group col-sm-4 {{ session('mode') == 'owner' ? '' : 'col-sm-offset-2' }}">
                    <input id="task" type="text" class="form-control" name="task" placeholder="Task Name" value="{{ \Request::input('task') }}">

                </div>

                @if(session('mode') == 'owner')
                <div class="form-group col-sm-3">
                    <select class="form-control" name="user" id="user">
                        <option value="">All Developers</option>
                        @foreach($userArr as $user)
                            <option value="{{ $user->id }}" {{ $user->id === Request::input('user')+0 ? 'selected' : '' }}>{{ $user->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            {{--</div>--}}
            {{--<br>--}}
            {{--<div class="row text-center">--}}
                <div class="form-group text-right {{ session('mode') == 'owner' ? '' : 'col-sm-offset-1' }}" style="margin-right: 15px;">
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <br>

    <div class="">

        <table class="table table-bordered" style="margin-top: 60px;">
            <thead class="">
            <tr>
                <th class="col-sm-4" scope="col">Project Name</th>
                <th class="col-sm-4" scope="col">Task Name</th>
                <th class="col-sm-3" scope="col">Employer</th>
                @if(session('mode') == 'owner')
                    <td class="col-sm-1">
                        <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal" id="add-task">
                            Add New Task
                        </button>
                    </td>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($taskArr as $task)
                <tr class="">
                    <td>{{ $task->project_name }}</td>
                    <td>{{ $task->name }}</td>
                    <td>{{ $task->firstname . ' ' . $task->lastname }}</td>
                    @if(session('mode') == 'owner')
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-md edit" data-toggle="modal" data-target="#myModal"
                                data-id="{{ $task->id }}">Edit
                        </button>
                    </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
    <div class="clearfix"></div>
    <div class="text-center">{{ $taskArr->appends(Request::all())->links() }}</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Task</h4>
            </div>
            <div class="modal-body">
                    <form class="col-sm-6 col-sm-offset-3 text-center" action="{{ route('save-task') }}" method="post" id="edit-form" >

                        <div class="form-group col-sm-12">
                            <label for="project">Project</label>
                            <select class="form-control" name="project" id="project">
                                <option value="">Chose Project</option>
                                @foreach($projectArr as $project)
                                    <option value="{{ $project->id }}" {{ $project->id === old('project')+0 ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                            <span class="alert-danger" id="project-error"></span>
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="task_name">Task</label>
                            <input type="text" class="form-control" id="task_name" name="task_name" value="{{ old('task_name') }}">
                            <span class="alert-danger" id="task-error"></span>
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="user">Team</label>
                            <select class="form-control" name="user" id="user">
                                <option value="">Chose Employer</option>
                                @foreach($userArr as $user)
                                    <option value="{{ $user->id }}" {{ $user->id === old('user')+0 ? 'selected' : '' }}>{{ $user->fullname }}</option>
                                @endforeach
                            </select>
                            <span class="alert-danger" id="user-error"></span>
                        </div>
                        <input type="hidden" name="task_id" id="task_id" value="">
                        <button type="submit" class="form-group btn btn-primary" id="save" style="">Save</button>
                    </form>
                <div class="clearfix"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

    <script>
        $('.edit').on('click', function () {
            $('#project-error').text('');
            $('#task-error').text('');
            $('#user-error').text('');
            $('#save').text('Update Task');
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'get',
                url: "{{ URL::to('/find-task') }}" + '/'+id,
                success: function (data) {
                    $('#task_name').val(data.task.name);
                    $('#task_id').val(data.task.id);

                    $("#project option").attr("selected", false);
                    $("#project option[value='"+data.task.project_id+"']").attr('selected', true);

                    $("#user option").attr("selected", false);
                    $("#user option[value='"+data.task.user_id+"']").attr('selected', true);

//                    window.scrollTo(0, 0);

                },
                error: function () {
                    alert('error');
                },
                dataType: "json"
            });
        });

        $('#edit-form').submit(function(event) {

            event.preventDefault();
            var formData = $(this).serializeArray();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type        : 'POST',
                url         : "{{ route('save-task') }}",
                data        : formData,
                dataType    : 'json',
                success     : function () {
                    location.reload();
                },
                error     : function (data) {
                    var errors = data.responseJSON;
                    if (errors.errors.project) {
                        $('#project-error').text(errors.errors.project[0]);
                    }
                    if (errors.errors.task_name) {
                        $('#task-error').text(errors.errors.task_name[0]);
                    }
                    if (errors.errors.user) {
                        $('#user-error').text(errors.errors.user[0]);
                    }
                }
            });

        });

        $('#add-task').on('click', function () {
            $("#project option").attr("selected", false);
            $("#user option").attr("selected", false);
            $('#task_name').val('');
            $('#task_id').val('');
            $('#project-error').text('');
            $('#task-error').text('');
            $('#user-error').text('');
            $('#save').text('Add Task');
        });

        $('#save').on('click', function () {
            $('#project-error').text('');
            $('#task-error').text('');
            $('#user-error').text('');
        });
    </script>

@endsection
