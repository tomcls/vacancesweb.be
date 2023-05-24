<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{ $title ?? 'Vacancesweb.be' }}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/scss/app.scss'])
        @livewireStyles
        @stack('css')
    </head>
    <body class="antialiased font-sans ">
        {{ $slot }}
        @livewireScripts
        @stack('scripts')
        @vite(['resources/js/app.js'])
    </body>
</html>