@extends('layouts.layout')

@section('content')
<div class="container col-sm-8 col-sm-offset-2" style="margin-top: 60px;">

        <div class="">
            <form class="form-inline" method="GET" action="{{ route('users') }}">
                <div class="row">
                    <div class="form-group col-sm-3">
                            <input id="firstname" type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ \Request::input('firstname') }}">
                    </div>

                    <div class="form-group col-sm-3 col-sm-offset-1">
                            <input id="lastname" type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ \Request::input('lastname') }}">
                    </div>

                    <div class="form-group col-sm-3 col-sm-offset-1">
                            <input id="email" type="text" class="form-control" name="email" placeholder="Email" value="{{ \Request::input('email') }}">
                    </div>
                    <div class="form-group text-right"  style="margin-right: 15px">
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
            <th class="col-sm-4" scope="col">First Name</th>
            <th class="col-sm-4" scope="col">Last Name</th>
            <th class="col-sm-4" scope="col">Email</th>
        </tr>
        </thead>
        <tbody>
        @foreach($userArr as $user)
            <tr>
                <td>{{ $user->firstname }}</td>
                <td>{{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="clearfix"></div>
<div class="text-center">{{ $userArr->appends(Request::all())->links() }}</div>
@endsection
