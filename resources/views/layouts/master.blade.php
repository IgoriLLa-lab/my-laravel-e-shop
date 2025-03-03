<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('main.title_online_shop') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/starter-template.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('index') }}">{{ __('main.title_online_shop') }}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li @if(\Illuminate\Support\Facades\Route::currentRouteNamed('index'))  class="active" @endif><a href="{{ route('index') }}">Все товары</a></li>
                <li @if(\Illuminate\Support\Facades\Route::currentRouteNamed('categories'))  class="active" @endif><a href="{{ route('categories') }}">Категории</a>
                </li>
                <li @if(\Illuminate\Support\Facades\Route::currentRouteNamed('basket'))  class="active" @endif><a href="{{ route('basket') }}">В корзину</a></li>
                {{--                <li><a href="{{ route('index') }}">Сбросить проект в начальное состояние</a></li>--}}
                <li><a href="{{ route('locale', __('main.set_language')) }}">{{ __('main.set_language') }}</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('login') }}" class="btn btn-primary">Войти</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-secondary">Регистрация</a></li>
                @endguest

                @auth
                    <li><a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Личный кабинет</a>
                    </li>
                @endauth
                {{--                @if (Route::has('login'))--}}
                {{--                    @auth--}}
                {{--                    @else--}}
                {{--                        @if (Route::has('register'))--}}
                {{--                        @endif--}}
                {{--                    @endauth--}}
                {{--                @endif--}}
                {{--                <li><a href="{{ url('/admin/home') }}">Панель администратора</a></li>--}}
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="starter-template">
        @if(session()->has('result'))
            <p class="alert alert-success">{{ session()->get('result') }}</p>
        @endif
        @if(session()->has('warning'))
            <p class="alert alert-warning">{{ session()->get('warning') }}</p>
        @endif
        @yield('content')
    </div>
</div>
</body>
</html>
