

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{ $title ?? 'Vacancesweb admin' }}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/scss/app.scss'])
        @livewireStyles
        @stack('css')
    </head>
    <body class="antialiased font-sans bg-gray-200">
        <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img class="mx-auto h-10 w-auto" src="https://www.vacancesweb.be/resources/img/logo-fr-black.svg" alt="Surge Logo" />
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @livewireScripts
        @stack('scripts')
        @vite(['resources/js/app.js'])
    </body>
</html>