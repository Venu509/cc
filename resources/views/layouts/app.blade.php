<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dream Career || @yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('template/css/main.css') }}?v={{ today()->format('Y-m-d H:i:s') }}" rel='stylesheet' type='text/css' />
        @yield('style')
    </head>
    <body>
        @includeIf('components.menus.header')

        <hr>

        @yield('content')

        @includeIf('components.menus.footer')

        <script src="{{ asset('template/js/jquery-1.11.1.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('template/js/main.js') }}?v={{ today()->format('Y-m-d H:i:s') }}"></script>
        @yield('script')
    </body>
</html>
