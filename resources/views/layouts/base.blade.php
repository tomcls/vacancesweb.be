<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <title>{{ $title ?? 'Vacancesweb.be' }}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/scss/app.scss'])
        @livewireStyles
        @stack('css')
    </head>
    <body class="antialiased font-sans bg-white h-full">
        <x-layouts.menu position="relative" background="bg-white" textColor="text-black" />
        <div class="flex min-h-full flex-col">
            {{$slot}}
        </div>
    @livewireScripts
    @stack('scripts')
    @vite(['resources/js/app.js'])
</body>
</html>