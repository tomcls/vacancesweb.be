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
                        <a href="{{route('me.profile')}}" class="flex-shrink-0 group block focus:outline-none">
                            <div class="flex items-center">
                                <div>
                                    <img class="inline-block h-10 w-10 rounded-full" src="{{ auth()->user()->avatarUrl() }}" alt="profile">
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
                                    <a href="{{route('admin.homepage')}}" :class="{ 'text-white  bg-gray-800': current=='admin.homepage', 'text-gray-400 ': current!='admin.homepage' }"class="hover:bg-gray-800  group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white">
                                        <x-icon.start class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                        Homepage 
                                    </a>
                                </li>
                                <li >
                                    <div x-data="{
                                        tabOpen: false,
                                     }">
                                        <button @click="tabOpen = !tabOpen" type="button" class=" hover:bg-gray-800 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-400 hover:text-white" aria-controls="sub-menu-geo" aria-expanded="false">
                                            <x-icon.user class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                            Users
                                              <!-- Expanded: "rotate-90 text-gray-500", Collapsed: "text-gray-400" -->
                                            <svg class="text-gray-400 ml-auto h-5 w-5 shrink-0"  :class="{ 'rotate-90 text-gray-500': tabOpen, 'text-gray-400': !(tabOpen) }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <!-- Expandable link section, show/hide based on state. -->
                                        <ul class="mt-1 px-2" id="sub-menu-geo" x-show="(current=='admin.users' || current=='admin.companies'|| current=='admin.newsletters')? tabOpen=true:tabOpen;" style="display: none" >
                                            <li>
                                                <a href="{{route('admin.users')}}" :class="{ ' text-white  bg-gray-800': current=='admin.users', 'text-gray-400 ': current!='admin.users' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white ">
                                                    Users
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.companies')}}" :class="{ ' text-white  bg-gray-800': current=='admin.companies', 'text-gray-400 ': current!='admin.companies' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Companies
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.newsletters')}}" :class="{ ' text-white  bg-gray-800': current=='admin.newsletters', 'text-gray-400 ': current!='admin.newsletters' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Newsletters
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li >
                                    <div x-data="{
                                        tabOpen: false,
                                     }">
                                        <button @click="tabOpen = !tabOpen" type="button" class=" hover:bg-gray-800 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-400 hover:text-white" aria-controls="sub-menu-1" aria-expanded="false">
                                            <x-icon.house class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                            Houses
                                              <!-- Expanded: "rotate-90 text-gray-500", Collapsed: "text-gray-400" -->
                                            <svg class="text-gray-400 ml-auto h-5 w-5 shrink-0"  :class="{ 'rotate-90 text-gray-500': tabOpen, 'text-gray-400': !(tabOpen) }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <!-- Expandable link section, show/hide based on state. -->
                                        <ul class="mt-1 px-2" id="sub-menu-1" x-show="(current=='admin.houses' || current=='admin.publications' || current=='admin.transactions' || current=='admin.packages' || current=='admin.package-users' || current=='admin.promos' || current=='admin.reservations' || current=='admin.icals' || current=='admin.house.contacts' || current=='admin.house.contactsbanned')? tabOpen=true:tabOpen;" style="display: none"  >
                                            <li>
                                                <a href="{{route('admin.houses')}}" :class="{ ' text-white  bg-gray-800': current=='admin.houses', 'text-gray-400 ': current!='admin.houses' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white ">
                                                    Houses
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.publications')}}" :class="{ ' text-white  bg-gray-800': current=='admin.publications', 'text-gray-400 ': current!='admin.publications' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Publications
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.transactions')}}" :class="{ ' text-white  bg-gray-800': current=='admin.transactions', 'text-gray-400 ': current!='admin.transactions' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Transactions
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.reservations')}}" :class="{ ' text-white  bg-gray-800': current=='admin.reservations', 'text-gray-400 ': current!='admin.reservations' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Reservations
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.icals')}}" :class="{ ' text-white  bg-gray-800': current=='admin.icals', 'text-gray-400 ': current!='admin.icals' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Icals
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.promos')}}" :class="{ ' text-white  bg-gray-800': current=='admin.promos', 'text-gray-400 ': current!='admin.promos' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Publication promo
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.packages')}}" :class="{ ' text-white  bg-gray-800': current=='admin.packages', 'text-gray-400 ': current!='admin.packages' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Packages
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.package-users')}}" :class="{ ' text-white  bg-gray-800': current=='admin.package-users', 'text-gray-400 ': current!='admin.package-users' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    User Packages
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.house.contacts')}}" :class="{ ' text-white  bg-gray-800': current=='admin.house.contacts', 'text-gray-400 ': current!='admin.house.contacts' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Contacts
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.house.contactsbanned')}}" :class="{ ' text-white  bg-gray-800': current=='admin.house.contactsbanned', 'text-gray-400 ': current!='admin.house.contactsbanned' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Contacts banned
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li >
                                    <div x-data="{
                                        tabOpen: false,
                                     }">
                                        <button @click="tabOpen = !tabOpen" type="button" class=" hover:bg-gray-800 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-400 hover:text-white" aria-controls="sub-menu-geo" aria-expanded="false">
                                            <x-icon.palm class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                            Holidays
                                              <!-- Expanded: "rotate-90 text-gray-500", Collapsed: "text-gray-400" -->
                                            <svg class="text-gray-400 ml-auto h-5 w-5 shrink-0"  :class="{ 'rotate-90 text-gray-500': tabOpen, 'text-gray-400': !(tabOpen) }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <!-- Expandable link section, show/hide based on state. -->
                                        <ul class="mt-1 px-2" id="sub-menu-geo" x-show="(current=='admin.holidays' || current=='admin.holidays.transactions' || current=='admin.holiday.contacts')? tabOpen=true:tabOpen;" style="display: none" >
                                            <li>
                                                <a href="{{route('admin.holidays')}}" :class="{ ' text-white  bg-gray-800': current=='admin.holidays', 'text-gray-400 ': current!='admin.holidays' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white ">
                                                    Holidays
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.holiday.transactions')}}" :class="{ ' text-white  bg-gray-800': current=='admin.holiday.transactions', 'text-gray-400 ': current!='admin.holiday.transactions' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    transactions
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.holiday.contacts')}}" :class="{ ' text-white  bg-gray-800': current=='admin.holiday.contacts', 'text-gray-400 ': current!='admin.holiday.contacts' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    contacts
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li >
                                    <div x-data="{
                                        tabOpen: false,
                                     }">
                                        <button @click="tabOpen = !tabOpen" type="button" class=" hover:bg-gray-800 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-400 hover:text-white" aria-controls="sub-menu-geo" aria-expanded="false">
                                            <x-icon.heart class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                            Partners
                                              <!-- Expanded: "rotate-90 text-gray-500", Collapsed: "text-gray-400" -->
                                            <svg class="text-gray-400 ml-auto h-5 w-5 shrink-0"  :class="{ 'rotate-90 text-gray-500': tabOpen, 'text-gray-400': !(tabOpen) }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <!-- Expandable link section, show/hide based on state. -->
                                        <ul class="mt-1 px-2" id="sub-menu-geo" x-show="(current=='admin.partner.homes' || current=='admin.partner.catalogs' || current==='admin.partner.holidays' || current==='admin.partner.boxes'|| current=='admin.partner.catalogs' || current == 'admin.partner.articles')? tabOpen=true:tabOpen;" style="display: none" >
                                            <li>
                                                <a href="{{route('admin.partner.homes')}}" :class="{ ' text-white  bg-gray-800': current=='admin.partner.homes', 'text-gray-400 ': current!='admin.partner.homes' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white ">
                                                    Heros
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.partner.catalogs')}}" :class="{ ' text-white  bg-gray-800': current=='admin.partner.catalogs', 'text-gray-400 ': current!='admin.partner.catalogs' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Catalogs
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.partner.holidays')}}" :class="{ ' text-white  bg-gray-800': current=='admin.partner.holidays', 'text-gray-400 ': current!='admin.partner.holidays' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Holidays
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.partner.articles')}}" :class="{ ' text-white  bg-gray-800': current=='admin.partner.articles', 'text-gray-400 ': current!='admin.partner.articles' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Articles
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.partner.boxes')}}" :class="{ ' text-white  bg-gray-800': current=='admin.partner.boxes', 'text-gray-400 ': current!='admin.partner.boxes' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Boxes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li >
                                    <div x-data="{
                                        tabOpen: false,
                                     }">
                                        <button @click="tabOpen = !tabOpen" type="button" class=" hover:bg-gray-800 flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold text-gray-400 hover:text-white" aria-controls="sub-menu-geo" aria-expanded="false">
                                            <x-icon.map class=" h-6 w-6 text-sky-500 group-focus:text-sky-300 transition ease-in-out duration-150"/>
                                            Geolocation
                                              <!-- Expanded: "rotate-90 text-gray-500", Collapsed: "text-gray-400" -->
                                            <svg class="text-gray-400 ml-auto h-5 w-5 shrink-0"  :class="{ 'rotate-90 text-gray-500': tabOpen, 'text-gray-400': !(tabOpen) }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <!-- Expandable link section, show/hide based on state. -->
                                        <ul class="mt-1 px-2" id="sub-menu-geo" x-show="(current=='admin.countries' || current=='admin.regions')? tabOpen=true:tabOpen;" style="display: none" >
                                            <li>
                                                <a href="{{route('admin.countries')}}" :class="{ ' text-white  bg-gray-800': current=='admin.countries', 'text-gray-400 ': current!='admin.countries' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white ">
                                                    Countries
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('admin.regions')}}" :class="{ ' text-white  bg-gray-800': current=='admin.regions', 'text-gray-400 ': current!='admin.regions' }" class="font-semibold hover:bg-gray-800 block rounded-md py-2  pl-9 text-sm leading-6 text-gray-400 hover:text-white">
                                                    Regions
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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