<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="">
        <header>
            @include('partials.header')
        </header>
        <div>
            @yield('content')
        </div>
        <footer>
            @include('partials.footer')
        </footer>
    </body>
</html>
