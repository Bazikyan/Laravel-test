<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laravel Test</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        #radioBtn .notActive{
            color: #3276b1;
            background-color: #fff;
        }

        #radioBtn {
            float: left;
            margin-top: 10px;
            margin-right: 20px;
        }
    </style>

</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Laravel test</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="{{ parse_url(url()->current(), PHP_URL_PATH) == '' ? 'active' : '' }}"><a href="{{ route('users') }}">Users</a></li>
                <li class="{{ parse_url(url()->current(), PHP_URL_PATH) == '/projects' ? 'active' : '' }}"><a href="{{ route('show-projects') }}">Projects</a></li>
                <li class="{{ parse_url(url()->current(), PHP_URL_PATH) == '/tasks' ? 'active' : '' }}"><a href="{{ route('show-tasks') }}">Tasks</a></li>
                <li class="{{ parse_url(url()->current(), PHP_URL_PATH) == '/reports' ? 'active' : '' }}"><a href="{{ route('show-reports') }}">Reports</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('register') }}"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="{{ route('login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                @endguest
                @auth

                    <div id="radioBtn" class="btn-group">
                        @if(session()->has('mode'))
                            <a class="btn btn-primary btn-sm {{ session('mode') == 'owner' ? 'active' : 'notActive' }}" data-toggle="mode" data-title="owner">Owner</a>
                            <a class="btn btn-primary btn-sm {{ session('mode') == 'employer' ? 'active' : 'notActive' }}" data-toggle="mode" data-title="employer">Employe</a>
                        @else
                            <a class="btn btn-primary btn-sm active" data-toggle="mode" data-title="owner">Owner</a>
                            <a class="btn btn-primary btn-sm notActive" data-toggle="mode" data-title="employer">Employer</a>
                        @endif
                    </div>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script>
    $('#radioBtn a').on('click', function(){
        var sel = $(this).data('title');
        $('a[data-toggle="mode"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
        $('a[data-toggle="mode"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
        $.ajax({
            type: 'get',
            url: "{{ route('save-mode') }}",
//            url: "/save-mode",
            data: {'sel': sel},
            success: function () {
//                location.reload();
                window.location = window.location.href.split("?")[0];
            },
            error: function () {
                alert('mode error');
            }
        });

    })
</script>

</body>
</html>
