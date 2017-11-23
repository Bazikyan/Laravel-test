@extends('layouts.layout')

@section('content')
<div class="container col-sm-8 col-sm-offset-2" style="margin-top: 60px;">

    <div class="">
        <form class="form-inline" method="GET" action="{{ route('show-projects') }}">
            <div class="row">
                <div class="form-group col-sm-4">
                    {{--<label for="project" class="col-md-4 control-label">Project Name</label>--}}

                    {{--<div class="col-md-6">--}}
                        <input id="project" type="text" class="form-control" name="project" placeholder="Project Name" value="{{ \Request::input('project') }}">
                    {{--</div>--}}
                </div>

                <div class="form-group col-sm-4 col-sm-offset-2">
                    {{--<label for="task" class="col-md-4 control-label">Task Name</label>--}}

                    {{--<div class="col-md-6">--}}
                        <input id="task" type="text" class="form-control" name="task" placeholder="Task Name" value="{{ \Request::input('task') }}">
                    {{--</div>--}}
                </div>
            {{--</div>--}}
            {{--<br>--}}
            {{--<div class="row text-center">--}}
                <div class="form-group text-right col-sm-2">
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <br>

    <table class="table table-bordered" style="margin-top: 60px; ">
        <thead class="thead-dark">
        <tr>
            <th class="col-sm-4" scope="col">Project Name</th>
            <th class="col-sm-4" scope="col">Owner Name</th>
            <th class="col-sm-3" scope="col">Owner Email</th>
            @if(session('mode') == 'owner')
                <td class="col-sm-1">
                    <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal" id="add-project">
                        Add New Project
                    </button>
                </td>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($projectArr as $project)
            <tr class="">
                <td>{{ $project->name }}</td>
                <td>{{ $project->firstname.' '.$project->lastname }}</td>
                <td>{{ $project->email }}</td>
                @if(session('mode') == 'owner')
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-md edit" data-toggle="modal" data-target="#myModal"
                                data-id="{{ $project->id }}">Edit
                        </button>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="clearfix"></div>
<div class="text-center">{{ $projectArr->appends(Request::all())->links() }}</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Project</h4>
            </div>
            <div class="modal-body">
                <form class="col-sm-6 col-sm-offset-3 text-center" action="{{ route('save-project') }}" method="post" id="edit-form" >

                    <div class="form-group col-sm-12">
                        <label for="project_name">Project Name</label>
                        <input type="text" class="form-control" id="project_name" name="project_name" value="">
                        <span class="alert-danger" id="project-error"></span>
                    </div>

                    <input type="hidden" name="project_id" id="project_id" value="">
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
        $('#save').text('Update Project');
        var id = $(this).attr('data-id');
        $.ajax({
            type: 'get',
            url: "{{ URL::to('/find-project') }}" + '/'+id,
            success: function (data) {
                $('#project_name').val(data.project.name);
                $('#project_id').val(data.project.id);
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
            url         : "{{ route('save-project') }}",
            data        : formData,
            dataType    : 'json',
            success     : function () {
                location.reload();
            },
            error     : function (data) {
                var errors = data.responseJSON;
                if (errors.errors.project_name) {
                    $('#project-error').text(errors.errors.project_name[0]);
                }
            }
        });

    });

    $('#add-project').on('click', function () {
        $('#project_name').val('');
        $('#project_id').val('');
        $('#project-error').text('');;
        $('#save').text('Add Project');
    });

    $('#save').on('click', function () {
        $('#project-error').text('');
    });
</script>

@endsection
