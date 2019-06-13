<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', '缇苏') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ route('admin.dashboard') }}">仪表盘</a>
            @else
                <a href="{{ route('admin.login') }}">登录</a>
                {{--<a href="{{ route('register') }}">Register</a>--}}
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">

            <a href="{{ route('admin.dashboard') }}"><img src="{{asset('/images/ts_logo.png')}}"></a>
        </div>
        <div class="links">
            <a href="{{ route('admin.dashboard') }}" target="_blank">广告系统</a>
            <a href="http://www.pblab.com" target="_blank">官方网站</a>
            <a href="https://tisu.eziyan.top" target="_blank">艺人检索</a>
            <a href="{{url('/ddoc')}}" target="_blank">数据字段</a>

        </div>
    </div>
</div>
</body>
</html>
