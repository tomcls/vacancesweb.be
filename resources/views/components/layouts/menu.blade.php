<header class="{{$position}} inset-x-0 top-0 z-50">
    
    <nav class="flex items-center justify-between p-6 lg:px-8  {{$background}} opacity-80" aria-label="Global">
      <x-notification />
      <div class="flex lg:flex-1">
        <a @click="sidebarOpen = true" href="{{route('home')}}" class="-m-1.5 p-1.5">
          <span class="sr-only">Your Company</span>
          <img class="h-6 w-auto " src="@include('components.icon.preload-image')" data-src="{{route('logo')}}?color=3b82f6" alt="">
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" @click.stop="sidebarOpen = true" class="transition ease-in-out duration-150 -m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-400">
          <span class="sr-only">Open main menu</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="{{route('houses')}}" class="text-sm font-semibold leading-6 {{$textColor}}">Locations</a>
        <a href="#" class="text-sm font-semibold leading-6 {{$textColor}}">Voyages</a>
        <a href="#" class="text-sm font-semibold leading-6 {{$textColor}}">Partenaires</a>
        <a href="#" class="text-sm font-semibold leading-6 {{$textColor}}">Articles</a>
        <a href="#" class="text-sm font-semibold leading-6 {{$textColor}}">Publier une location</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="{{(auth()->check()) ? route('profile') : route('auth.login')}}" class="text-sm font-semibold leading-6 text-left {{$textColor}}">{!!(auth()->check()) ? auth()->user()->firstname."<br><small>"."Mon profile</small>": 'Connexion'!!} <span class="text-pink-500"  aria-hidden="true">&rarr;</span></a>
      </div>
    </nav>
    <!-- Mobile menu, show/hide based on menu open state. -->
    <div class="lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen"  style="display: none;">
      <!-- Background backdrop, show/hide based on slide-over state. -->
      <div @click="sidebarOpen = false" class="fixed inset-0 z-50"
          x-transition:enter="transition-opacity ease-linear duration-300" 
          x-transition:enter-start="opacity-0" 
          x-transition:enter-end="opacity-100" 
          x-transition:leave="transition-opacity ease-linear duration-300" 
          x-transition:leave-start="opacity-100" 
          x-transition:leave-end="opacity-0"></div>
      <div @click="sidebarOpen = false" x-show="sidebarOpen" class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10"
          x-transition:enter="transition-opacity ease-linear duration-300" 
          x-transition:enter-start="opacity-0" 
          x-transition:enter-end="opacity-100" 
          x-transition:leave="transition-opacity ease-linear duration-300" 
          x-transition:leave-start="opacity-100" 
          x-transition:leave-end="opacity-0" >
        <div class="flex items-center justify-between">
          <a @click="sidebarOpen = false" href="#" class="-m-1.5 p-1.5">
            <span class="sr-only">Your Company</span>
            <img class="h-8 w-auto " src="@include('components.icon.preload-image')" data-src="{{route('logo')}}?color=3f76f4&square=true"  alt="">
          </a>
          <button 
          type="button"  
          class="-m-2.5 rounded-md p-2.5 text-gray-400"
          @click="sidebarOpen = false" x-show="sidebarOpen">
            <span class="sr-only">Close menu</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-gray-500/25">
            <div class="space-y-2 py-6">
              <a href="{{route('houses')}}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Locations</a>
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Voyages</a>
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Partenaires</a>
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Articles</a>
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Publier une location</a>
            </div>
            <div class="py-6">
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-white hover:bg-gray-800">Connexion</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>