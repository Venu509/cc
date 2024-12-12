<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link href="{{ asset('css/themes/theme.css') }}" rel="stylesheet" type="text/css">

        <link href="{{ mix('css/app.css') }}?v={{ today()->format('Y-m-d H:i:s') }}" rel="stylesheet">

        @routes
        <script src="{{ mix('js/app.js') }}?v={{ today()->format('Y-m-d H:i:s') }}" defer></script>
        @inertiaHead
        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
        <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0" />
        <meta http-equiv="Pragma" content="no-cache" />
    </head>
    <body class="font-sans antialiased">
        @inertia
        <script src="{{ asset('js/themes/head.js') }}"></script>
    </body>
</html>
