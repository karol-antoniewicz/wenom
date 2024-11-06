<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="{{ (config('session.lifetime') + 0.1) * 60 }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="favicon.svg">
        @vite('resources/js/app.ts')
        @routes
        @inertiaHead
    </head>
    <body class="font-sans antialiased relative" style="overflow: hidden;">
        @inertia
    </body>
</html>
