<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{ $title ?? 'Vacancesweb.be' }}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf_token" value="{{ csrf_token() }}"/>
        @vite(['resources/scss/app.scss'])
        
        @livewireStyles
    </head>
    <body>
        {{ $slot }}
        @livewireScripts
        @vite(['resources/js/app.js'])
    </body>
</html>