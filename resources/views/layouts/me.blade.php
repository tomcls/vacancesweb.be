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
       
    <div class="h-screen flex overflow-hidden bg-white" 
        x-data="{
            sidebarOpen: false,
            tabOpen: false,
            current:'{{ Route::currentRouteName() }}'
         }"
        @keydown.window.escape="sidebarOpen = false">
        <!-- Off-canvas menu for mobile -->
        <div x-show="sidebarOpen" class="md:hidden" style="display: none;">
            <div class="fixed inset-0 flex z-40">
                <div @click="sidebarOpen = false" x-show="sidebarOpen" x-description="Off-canvas menu overlay, show/hide based on off-canvas menu state." x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0" style="display: none;">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>
                <div x-show="sidebarOpen" x-description="Off-canvas menu, show/hide based on off-canvas menu state." x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-indigo-800" style="display: none;">
                    <div class="absolute top-0 right-0 -mr-14 p-1">
                        <button x-show="sidebarOpen" @click="sidebarOpen = false" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600" aria-label="Close sidebar" style="display: none;">
                            <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <img class="h-8 w-auto" src="/logo/logo.svg" alt="Workflow">
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <a href="/dashboard" class="group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-white bg-indigo-900 focus:outline-none focus:bg-indigo-700 transition ease-in-out duration-150">
                                <svg class="mr-4 h-6 w-6 text-sky-500 group-hover:text-sky-300 group-focus:text-sky-300 transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6"></path>
                                </svg>
                                Users
                            </a>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-t border-indigo-700 p-4">
                        <a href="/profile" class="flex-shrink-0 group block focus:outline-none">
                            <div class="flex items-center">
                                <div>
                                    <img class="inline-block h-10 w-10 rounded-full" src="{{ auth()->user()->avatarUrl() }}" alt="">
                                </div>
                                <div class="ml-3">
                                    <p class="text-base leading-6 font-medium text-white">
                                        {{ auth()->user()->firstname }}  {{ auth()->user()->lastname }}
                                    </p>
                                    <p class="text-sm leading-5 font-medium text-sky-300 group-hover:text-indigo-100 group-focus:underline transition ease-in-out duration-150">
                                        View profile
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex-shrink-0 w-14">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 border-r border-gray-200 bg-gray-900">
                <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200  px-6">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <a href="{{route('home')}}"><img class="h-11 w-auto" src="/logo/logo-fr-white.svg" alt="Vacancesweb admin"></a> 
                    </div>
                    <!-- Sidebar component, swap this element with another sidebar if you like -->
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                          <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{route('me.dashboard')}}" :class="{ 'text-white  bg-gray-800': current=='me.dashboard', 'text-gray-400 ': current!='me.dashboard' }"class="hover:bg-gray-800  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white">
                                        <x-icon.stats class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                        Dashboard 
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('me.profile')}}" :class="{ 'text-white  bg-gray-800': current=='me.profile', 'text-gray-400 ': current!='me.profile' }"class="hover:bg-gray-800  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white">
                                        <x-icon.user class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                        My profile 
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('me.houses')}}" :class="{ 'text-white  bg-gray-800': current=='me.houses', 'text-gray-400 ': current!='me.houses' }"class="hover:bg-gray-800  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white">
                                        <x-icon.house class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                        My rentals 
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('me.messages')}}" :class="{ 'text-white  bg-gray-800': current=='me.messages', 'text-gray-400 ': current!='me.messages' }"class="hover:bg-gray-800  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white">
                                        <x-icon.mail class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                        My messages 
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('me.invoices')}}" :class="{ 'text-white  bg-gray-800': current=='me.invoices', 'text-gray-400 ': current!='me.invoices' }"class="hover:bg-gray-800  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white">
                                        <x-icon.document class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                        My invoices 
                                    </a>
                                </li>
                            </ul>
                          </li>
                          <li class="-mx-6 mt-auto">
                            <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-gray-800">
                                <img class="h-8 w-8 rounded-full bg-gray-800" src="{{ auth()->user()->avatarUrl() }}" alt="">
                                <span class="text-sm leading-5 font-medium text-white">
                                    {{ auth()->user()->firstname }}  {{ auth()->user()->lastname }}
                                </span>
                            </a>
                          </li>
                        </ul>
                      </nav>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3">
                <button @click.stop="sidebarOpen = true" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150" aria-label="Open sidebar">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 focus:outline-none md:py-6" tabindex="0" x-data="" x-init="$el.focus()">
                <div class="mx-auto px-4 sm:px-6 md:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

         <x-notification /> 
    </div>
    @livewireScripts
    @stack('scripts')
    @vite(['resources/js/app.js'])
</body>
</html>