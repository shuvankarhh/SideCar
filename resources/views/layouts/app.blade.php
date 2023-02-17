<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ env('cssOverWrite', '') }}" rel="stylesheet">

</head>
<body >

    <div x-data={loading:false}>
        <div class="loading" x-show="loading" x-on:loading.window="loading = !loading" >Loading...</div>
    </div>
    
    <header>

        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 navbar-light text-white bg-dark shadow-sm" id="mainNav">
            <div class="container-fluid px-4 px-lg-5">
                <a class="navbar-brand" href="{{ route('Setup') }}">
                    <img src="{{ asset('images/sidecar_logo.png')}}" alt="{{ config('app.name', 'Laravel') }}" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                    <x-menuRight></x-menuRight>
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto text-white">

                        
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                        @else
                           
                            <x-menuLeft></x-menuLeft>
                            
                            <x-setupDetails></x-setupDetails>
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    
                                    @role('admin')
                                        <a class="dropdown-item" href="{{ route('users') }}">{{ __('User management') }}</a>
                                        <a class="dropdown-item" href="{{ route('userCreate') }}">{{ __('Create User') }}</a>
                                    @endrole
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                    
                </div>
            </div>
        </nav>

    </header>
    <div id="app">

        <main class="py-4 login_bg ">
            @yield('content')
        </main>
    </div>
</body>
</html>
