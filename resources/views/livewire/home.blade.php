<div class="bg-white"
    x-data="{
        sidebarOpen: false,
        hasClass: function(element, className) {
          return (' ' + element.className + ' ').indexOf(' ' + className+ ' ') > -1;
        },
        loadImages: function() {
        
          var observer = new IntersectionObserver(
              (entries, observer) => {
                  entries.forEach(entry => {
                      if (entry.intersectionRatio > 0.0) {
                          img = entry.target;
                          
                          if (!this.hasClass(img,'loaded') && !this.hasClass(img,'logo') ) {
                                img.setAttribute('src', img.dataset.src);
                                img.className += ' loaded';
                          }
                      }
                  });
              },
              {}
          )
          for (let img of document.getElementsByTagName('img')) {
              observer.observe(img);
          }
      }
    }"
    x-init="loadImages()"
    @keydown.window.escape="sidebarOpen = false" >
    <header class="absolute inset-x-0 top-0 z-50">
      <nav class="flex items-center justify-between p-6 lg:px-8  bg-black opacity-80" aria-label="Global">
        <div class="flex lg:flex-1">
          <a @click="sidebarOpen = true" href="#" class="-m-1.5 p-1.5">
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
          <a href="#" class="text-sm font-semibold leading-6 text-white">Locations</a>
          <a href="#" class="text-sm font-semibold leading-6 text-white">Voyages</a>
          <a href="#" class="text-sm font-semibold leading-6 text-white">Partenaires</a>
          <a href="#" class="text-sm font-semibold leading-6 text-white">Articles</a>
          <a href="#" class="text-sm font-semibold leading-6 text-white">Publier une location</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <a href="{{route('auth.login')}}" class="text-sm font-semibold leading-6 text-white">Connexion <span class="text-pink-500"  aria-hidden="true">&rarr;</span></a>
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
                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Locations</a>
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
  
    <div class="relative isolate overflow-hidden pt-14">
      <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['hero1']['image']}}" alt="" class="absolute inset-0 -z-10 sm:h-full w-full object-cover">
      {{-- <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
      </div> --}}
      <div class="mx-auto max-w-7xl pt-20 pb-16 sm:py-48 lg:py-56 ">
        <div class="absolute h-1/3  z-10 mt-20 sm:relative sm:mt-0  sm:mb-16 sm:flex sm:justify-start bg-white rounded-lg px-4 py-3 shadow-2xl inset-0  max-w-5xl mx-2 sm:mx-auto ">
          <div class="flex flex-col   mx-auto">
            <x-input.group  for="searchHoliday" label="">
              <x-input.location 
                  wire:model.debounce.450ms="locationSearch" 
                  wire:keyup.debounce.450ms="locationsResult"
                  id="searchHoliday" 
                  name="searchHoliday" 
                  wireModel="locationId" 
                  :rows="$locations" 
                  
                  placeholder="Où partez-vous ? Référence ?" >
                  <x-slot:icon>
                    <x-icon.map class="flex-shrink-0 text-sky-600"/>
                  </x-slot:icon>
              </x-input.location>
            </x-input.group>
            <div class="pt-2 flex flex-col sm:flex-row sm:space-x-2 relative rounded-full px-0  py-1 text-sm leading-6 text-gray-600 ring-1 ring-white/10 hover:ring-white/20">
              
              <x-input.group  for="searchTypes" label="Types de séjour"  class="mt-2 sm:mt-0">
                  <x-input.search-types id="searchTypes" />
              </x-input.group>
              <div class="flex flex-row space-x-1 sm:space-x-2">
                  <x-input.group  for="searchFrom" label="Date d'arrivée" class="mt-2 sm:mt-0">
                    <div class="w-44 sm:w-48">
                        <div class="relative mt-2 rounded-md shadow-sm">
                          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-icon.calendar class="text-blue-500"/>
                          </div>
                          <input type="text" name="searchFrom" id="searchFrom" class="block w-40 sm:w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Date d'arrivée">
                        </div>
                      </div>
                </x-input.group>
                <x-input.group  for="searchTo" label="Date de départ" class="mt-2 sm:mt-0">
                    <div  class="w-44 sm:w-48">
                        <div class="relative mt-2 rounded-md shadow-sm">
                          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-icon.calendar class="text-blue-500" />
                          </div>
                          <input type="text" name="searchTo" id="searchTo" class="block w-40 sm:w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Date de départ">
                        </div>
                      </div>
                </x-input.group>
              </div>
              <x-input.group  for="searchPeople" label="Nombre de vacanciers" class="mt-2 sm:mt-0">
                  <div  class="sm:w-48">
                      <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                          <x-icon.user class="text-blue-500" />
                        </div>
                        <input type="text" name="searchPeople" id="searchPeople" class="block w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Nombre de vacanciers">
                      </div>
                    </div>
              </x-input.group>
              <button type="button" class="sm:inline-flex items-center gap-x-2 rounded-md bg-blue-600 px-3.5 py-3 my-2 sm:mt-7  text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Rechercher
                <x-icon.right />
              </button>

            </div>
          </div>

        </div>
        <div class="hidden pl-2 pt-2 sm:flex flex-row bg-white  relative w-40 text-center font-bold py-1 rounded-t" >
            <x-icon.guide class=" text-pink-500"/>
            Suivez le guide
        </div>
        <div class="text-center  rounded sm:border-8 border-white rounded-tl-none rounded-br-none">
            
            <div class=" flex flex-wrap ">
                <div class="hidden sm:flex sm:w-1/2 flex-wrap ">
                    <div class=" h-96 w-full  md:p-0  sm:border-r-8 border-white">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  >
                                
                            </a>
            
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-8 self-end rounded-b-lg">
                                <h2 class="text-2xl font-semibold leading-6 text-white text-left">{{$homepage[$lang]['hero1']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex sm:w-1/2 flex-wrap ">
                    
                    <div class="block w-full h-60 sm:w-1/2 sm:h-1/2 p-0 md:p-0 sm:border-b-8 sm:border-r-8 border-white">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  >
                                <img class="absolute h-full w-full  object-cover object-center " src="@include('components.icon.preload-image')"  data-src="{{$homepage[$lang]['hero2']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end sm:rounded-b-lg">
                                <h2 class="text-md font-semibold leading-6 text-white text-opacity-75 text-left">{{$homepage[$lang]['hero2']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-60 sm:w-1/2 sm:h-1/2 p-0 md:p-0 sm:border-b-8 border-white">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a >
                                <img class="absolute h-full w-full  object-cover object-center" src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['hero3']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end sm:rounded-b-lg">
                                <h2 class="text-md font-semibold leading-6 text-white text-opacity-75 text-left">{{$homepage[$lang]['hero3']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-60 sm:w-1/2  sm:h-1/2 p-0 md:p-0 sm:border-r-8 border-white">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  >
                            <img class="absolute h-full w-full  object-cover object-center" src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['hero4']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end sm:rounded-b-lg">
                                <h2 class="text-md font-semibold leading-6 text-white text-opacity-75 text-left">{{$homepage[$lang]['hero4']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="w-full h-60 sm:w-1/2  sm:h-1/2 p-0 md:p-0">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  >
                                <img class="absolute h-full w-full  object-cover object-center" src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['hero5']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-md font-semibold leading-6 text-white text-opacity-75 text-left">{{$homepage[$lang]['hero5']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden pl-3 pt-2 sm:flex bg-white relative w-48 text-center font-bold py-1 rounded-b float-right" >
            Tous les reportages
            <x-icon.right class=" text-pink-500 pt-2 pl-2 "/>
        </div>
      </div>
      {{-- <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
        <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
      </div> --}}
    </div>

    <div class="mt-5 bg-white mx-auto max-w-7xl mb-7">
        <h2 class="text-2xl font-semibold text-gray-900 text-center py-7 sm:text-4xl">Offres Privilèges</h2>
        <ul role="list" class="mx-2 sm:mx-0 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
            <li class="relative ">
                <div class="group relative border rounded-lg pb-5 h-full">
                    <div class=" aspect-h-3 aspect-w-4 overflow-hidden rounded-lg rounded-b-none bg-gray-100">
                      <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['holiday1']['image']}}"  class="object-cover object-center">
                    </div>
                    <h3 class="text-slate-600 pt-2 pl-3">
                        <a >
                          <span aria-hidden="true" class="absolute inset-0"></span>
                          {{$homepage[$lang]['holiday1']['type']}}
                        </a>
                    </h3>
                    <h3 class="text-blue-600 pl-3">
                        <a >
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$homepage[$lang]['holiday1']['title']}}
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 pl-3">à.p.d. <strong class="text-lg text-slate-800">€{{$homepage[$lang]['holiday1']['price']}}</strong> </p>
                    <p class="mt-3 text-sm text-gray-500 pl-3">@if(!empty($homepage[$lang]['holiday1']['info']))<x-icon.privilege class="text-lg text-slate-800 pr-1 pb-1  " />@endif {{$homepage[$lang]['holiday1']['info']}}</p>
                </div>
            </li>
            <li class="relative ">
                <div class="group relative border rounded-lg pb-5 h-full">
                    <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg rounded-b-none bg-gray-100">
                      <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['holiday2']['image']}}"  class="object-cover object-center">
                    </div>
                    <h3 class="text-slate-600 pt-2 pl-3">
                        <a >
                          <span aria-hidden="true" class="absolute inset-0"></span>
                          {{$homepage[$lang]['holiday2']['type']}}
                        </a>
                    </h3>
                    <h3 class="text-blue-600 pl-3">
                        <a >
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$homepage[$lang]['holiday2']['title']}}
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 pl-3">à.p.d. <strong class="text-lg text-slate-800">€{{$homepage[$lang]['holiday2']['price']}}</strong> </p>
                    <p class="mt-3 text-sm text-gray-500 pl-3">@if(!empty($homepage[$lang]['holiday2']['info']))<x-icon.privilege class="text-lg text-slate-800 pr-1 pb-1  " />@endif  {{$homepage[$lang]['holiday2']['info']}}</p>
                </div>
            </li>
            <li class="relative ">
                <div class="group relative border rounded-lg pb-5 h-full">
                    <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg rounded-b-none bg-gray-100">
                      <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['holiday3']['image']}}"  class="object-cover object-center">
                    </div>
                    <h3 class="text-slate-600 pt-2 pl-3">
                        <a >
                          <span aria-hidden="true" class="absolute inset-0"></span>
                          {{$homepage[$lang]['holiday3']['type']}}
                        </a>
                    </h3>
                    <h3 class="text-blue-600 pl-3">
                        <a >
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$homepage[$lang]['holiday3']['title']}}
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 pl-3">à.p.d. <strong class="text-lg text-slate-800">€{{$homepage[$lang]['holiday3']['price']}}</strong> </p>
                    <p class="mt-3 text-sm text-gray-500 pl-3">@if(!empty($homepage[$lang]['holiday3']['info']))<x-icon.privilege class="text-lg text-slate-800 pr-1 pb-1  " />@endif {{$homepage[$lang]['holiday3']['info']}}</p>
                </div>
            </li>
            <li class="relative ">
                <div class="group relative border rounded-lg pb-5 h-full">
                    <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg rounded-b-none bg-gray-100">
                      <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['holiday4']['image']}}"  class="object-cover object-center">
                    </div>
                    <h3 class="text-slate-600 pt-2 pl-3">
                        <a >
                          <span aria-hidden="true" class="absolute inset-0"></span>
                          {{$homepage[$lang]['holiday4']['type']}}
                        </a>
                    </h3>
                    <h3 class="text-blue-600 pl-3">
                        <a >
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$homepage[$lang]['holiday4']['title']}}
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 pl-3 ">à.p.d. <strong class="text-lg text-slate-800">€{{$homepage[$lang]['holiday4']['price']}}</strong> </p>
                    <p class="mt-3 text-sm text-gray-500 pl-3 ">@if(!empty($homepage[$lang]['holiday4']['info']))<x-icon.privilege class="text-lg text-slate-800 pr-1 pb-1  " />@endif {{$homepage[$lang]['holiday4']['info']}}</p>
                </div>
            </li>
            <!-- More files... -->
            </ul>
    </div>
    <!--Partners -->
    <div class="bg-white py-10 ">
        <h2 class="text-2xl font-semibold text-gray-900 text-center py-7 sm:text-4xl">Nos partenaires</h2>
        <div class="bg-white py-10 ">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 ">
              <div class="-mx-6 grid grid-cols-2 gap-0.5 overflow-hidden sm:mx-0 sm:rounded-2xl md:grid-cols-6 border rounded-md">
                <div class=" p-6 sm:pt-10">
                  <img class=" max-h-16 w-full object-contain" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/vlan-voyage.png" alt="vlan voyage"  >
                </div>
                <div class=" p-6 sm:pt-10">
                  <img class="max-h-12 w-full object-contain" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/7dimanche.jpg" alt="7dimanche"  height="48">
                </div>
                <div class=" p-6 sm:pt-12">
                  <img class="max-h-12 w-full object-contain" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/lesoir.jpg" alt="lesoir"  height="48">
                </div>
                <div class=" p-6 sm:pt-10">
                  <img class="max-h-12 w-full object-contain" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/soirmag.jpg" alt="soirmag"  height="48">
                </div>
                <div class=" p-6  sm:pt-14">
                  <img class="max-h-12 w-full object-contain" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/sudinfo-voyage.jpg" alt="sudinfo"  height="48">
                </div>
                <div class=" p-6 sm:pt-10">
                  <img class="max-h-16 w-full object-contain" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/cinevoyages.jpg" alt="cinevoyages"  height="75">
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- Blog section -->
    <div class="bg-gray-900 w-full py-10">

        <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-20 lg:px-8 ">
            <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
              <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl text-center">From the blog</h2>
              <p class="mt-2 text-lg leading-8 text-gray-400 text-center">Vel dolorem qui facilis soluta sint aspernatur totam cumque.</p>
            </div>
            <div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
              <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
                <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['post1']['image']}}" alt="" class="absolute inset-0 -z-10 sm:h-full w-full object-cover">
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
                <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
      
                <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
                  <time datetime="2020-03-16" class="mr-8">Mar 16, 2020</time>
                  <div class="-ml-4 flex items-center gap-x-4">
                    <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                      <circle cx="1" cy="1" r="1" />
                    </svg>
                    <div class="flex gap-x-2.5">
                      <img src="@include('components.icon.preload-image')" data-src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                      Michael Foster
                    </div>
                  </div>
                </div>
                <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                  <a href="#">
                    <span class="absolute inset-0"></span>
                    {{$homepage[$lang]['post1']['title']}}
                  </a>
                </h3>
              </article>
              <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
                <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['post2']['image']}}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
                <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
      
                <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
                  <time datetime="2020-03-16" class="mr-8">Mar 16, 2020</time>
                  <div class="-ml-4 flex items-center gap-x-4">
                    <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                      <circle cx="1" cy="1" r="1" />
                    </svg>
                    <div class="flex gap-x-2.5">
                      <img src="@include('components.icon.preload-image')" data-src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                      Michael Foster
                    </div>
                  </div>
                </div>
                <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                  <a href="#">
                    <span class="absolute inset-0"></span>
                    {{$homepage[$lang]['post2']['title']}}
                  </a>
                </h3>
              </article>
              <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
                <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['post3']['image']}}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
                <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
      
                <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
                  <time datetime="2020-03-16" class="mr-8">Mar 16, 2020</time>
                  <div class="-ml-4 flex items-center gap-x-4">
                    <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                      <circle cx="1" cy="1" r="1" />
                    </svg>
                    <div class="flex gap-x-2.5">
                      <img src="@include('components.icon.preload-image')" data-src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                      Michael Foster
                    </div>
                  </div>
                </div>
                <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                  <a href="#">
                    <span class="absolute inset-0"></span>
                    {{$homepage[$lang]['post3']['title']}}
                  </a>
                </h3>
              </article>
      
              <!-- More posts... -->
            </div>
          </div>
    </div>
    <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-20 lg:px-8 ">
        <div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
            <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['post4']['image']}}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
            <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
            <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
  
            <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
              <time datetime="2020-03-16" class="mr-8">Mar 16, 2020</time>
              <div class="-ml-4 flex items-center gap-x-4">
                <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                  <circle cx="1" cy="1" r="1" />
                </svg>
                <div class="flex gap-x-2.5">
                  <img src="@include('components.icon.preload-image')" data-src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                  Michael Foster
                </div>
              </div>
            </div>
            <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
              <a href="#">
                <span class="absolute inset-0"></span>
                {{$homepage[$lang]['post4']['title']}}
              </a>
            </h3>
          </article>
          <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
            <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['post5']['image']}}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
            <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
            <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
  
            <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
              <time datetime="2020-03-16" class="mr-8">Mar 16, 2020</time>
              <div class="-ml-4 flex items-center gap-x-4">
                <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                  <circle cx="1" cy="1" r="1" />
                </svg>
                <div class="flex gap-x-2.5">
                  <img src="@include('components.icon.preload-image')" data-src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                  Michael Foster
                </div>
              </div>
            </div>
            <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
              <a href="#">
                <span class="absolute inset-0"></span>
                {{$homepage[$lang]['post5']['title']}}
              </a>
            </h3>
          </article>
          <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
            <img src="@include('components.icon.preload-image')" data-src="{{$homepage[$lang]['post6']['image']}}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover">
            <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
            <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
  
            <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
              <time datetime="2020-03-16" class="mr-8">Mar 16, 2020</time>
              <div class="-ml-4 flex items-center gap-x-4">
                <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                  <circle cx="1" cy="1" r="1" />
                </svg>
                <div class="flex gap-x-2.5">
                  <img src="@include('components.icon.preload-image')" data-src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-6 w-6 flex-none rounded-full bg-white/10">
                  Michael Foster
                </div>
              </div>
            </div>
            <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
              <a href="#">
                <span class="absolute inset-0"></span>
                {{$homepage[$lang]['post6']['title']}}
              </a>
            </h3>
          </article>
  
          <!-- More posts... -->
        </div>
      </div>
    <!-- Testimonial section -->
    <div class="relative z-10 mt-32 bg-gray-900 pb-20 sm:mt-56 sm:pb-24 xl:pb-0">
      <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute left-[calc(50%-19rem)] top-[calc(50%-36rem)] transform-gpu blur-3xl">
          <div class="aspect-[1097/1023] w-[68.5625rem] bg-gradient-to-r from-[#148be0] to-[#0e0872] opacity-25" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
      </div>
      <div class="mx-auto flex max-w-7xl flex-col items-center gap-x-8 gap-y-10 px-6 sm:gap-y-8 lg:px-8 xl:flex-row xl:items-stretch">
        <div class="-mt-8 w-full max-w-2xl xl:-mb-8 xl:w-96 xl:flex-none">
          <div class="relative aspect-[2/1] h-full md:-mx-8 xl:mx-0 xl:aspect-auto xl:w-96" >
            <img class="absolute inset-0 h-full w-full rounded-2xl bg-gray-800 object-cover shadow-2xl" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/home/oman.jpg" alt="">
          </div>
        </div>
        <div class="w-full max-w-2xl xl:max-w-none xl:flex-auto xl:px-16 xl:py-24">
          <figure class="relative isolate pt-6 sm:pt-12">
            <svg viewBox="0 0 162 128" fill="none" aria-hidden="true" class="absolute left-0 top-0 -z-10 h-32 stroke-white/20">
              <path id="b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb" d="M65.5697 118.507L65.8918 118.89C68.9503 116.314 71.367 113.253 73.1386 109.71C74.9162 106.155 75.8027 102.28 75.8027 98.0919C75.8027 94.237 75.16 90.6155 73.8708 87.2314C72.5851 83.8565 70.8137 80.9533 68.553 78.5292C66.4529 76.1079 63.9476 74.2482 61.0407 72.9536C58.2795 71.4949 55.276 70.767 52.0386 70.767C48.9935 70.767 46.4686 71.1668 44.4872 71.9924L44.4799 71.9955L44.4726 71.9988C42.7101 72.7999 41.1035 73.6831 39.6544 74.6492C38.2407 75.5916 36.8279 76.455 35.4159 77.2394L35.4047 77.2457L35.3938 77.2525C34.2318 77.9787 32.6713 78.3634 30.6736 78.3634C29.0405 78.3634 27.5131 77.2868 26.1274 74.8257C24.7483 72.2185 24.0519 69.2166 24.0519 65.8071C24.0519 60.0311 25.3782 54.4081 28.0373 48.9335C30.703 43.4454 34.3114 38.345 38.8667 33.6325C43.5812 28.761 49.0045 24.5159 55.1389 20.8979C60.1667 18.0071 65.4966 15.6179 71.1291 13.7305C73.8626 12.8145 75.8027 10.2968 75.8027 7.38572C75.8027 3.6497 72.6341 0.62247 68.8814 1.1527C61.1635 2.2432 53.7398 4.41426 46.6119 7.66522C37.5369 11.6459 29.5729 17.0612 22.7236 23.9105C16.0322 30.6019 10.618 38.4859 6.47981 47.558L6.47976 47.558L6.47682 47.5647C2.4901 56.6544 0.5 66.6148 0.5 77.4391C0.5 84.2996 1.61702 90.7679 3.85425 96.8404L3.8558 96.8445C6.08991 102.749 9.12394 108.02 12.959 112.654L12.959 112.654L12.9646 112.661C16.8027 117.138 21.2829 120.739 26.4034 123.459L26.4033 123.459L26.4144 123.465C31.5505 126.033 37.0873 127.316 43.0178 127.316C47.5035 127.316 51.6783 126.595 55.5376 125.148L55.5376 125.148L55.5477 125.144C59.5516 123.542 63.0052 121.456 65.9019 118.881L65.5697 118.507Z" />
              <use href="#b56e9dab-6ccb-4d32-ad02-6b4bb5d9bbeb" x="86" />
            </svg>
            <blockquote class="text-xl font-semibold leading-8 text-white sm:text-2xl sm:leading-9">
              <p>Gravida quam mi erat tortor neque molestie. Auctor aliquet at porttitor a enim nunc suscipit tincidunt nunc. Et non lorem tortor posuere. Nunc eu scelerisque interdum eget tellus non nibh scelerisque bibendum.</p>
            </blockquote>
            <figcaption class="mt-8 text-base">
              <div class="font-semibold text-white">Pierre Kroll</div>
              <div class="mt-1 text-gray-400">Dessinateur</div>
            </figcaption>
          </figure>
        </div>
      </div>
    </div>
    <!-- Content section -->
    <div class="mt-32 overflow-hidden sm:mt-40 pb-10">
        <div class="mx-auto max-w-7xl px-6 lg:flex lg:px-8">
          <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-12 gap-y-16 lg:mx-0 lg:min-w-full lg:max-w-none lg:flex-none lg:gap-y-8">
            <div class="lg:col-end-1 lg:w-full lg:max-w-lg lg:pb-8">
              <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Wallonie</h2>
              <p class="mt-6 text-xl leading-8 text-gray-600">Laissez-vous guider... Un séjour pour déconnecter dans les hautes Fagnes, Un moment de détente en bord de Meuse, un weekend à vélo, un trail en Haute Ardenne</p>
              <p class="mt-6 text-base leading-7 text-gray-600">Consultez tous nos articles sur le sujet.<br/> Vous n'avez toujours d'idées?.<br/> <a href="https://www.rendezvousalhotel.be" class="text-pink-500">Rendezvousalhotel.be</a> vous propose des offres exclusives dans les meilleurs hôtels de Wallonie</p>
            </div>
            <div class="flex flex-wrap items-start justify-end gap-6 sm:gap-8 lg:contents">
                <div class="w-0 flex-auto lg:ml-auto lg:w-auto lg:flex-none lg:self-end">
                    <a href="https://www.vacancesweb.be/liste-des-themes/wallonie-belgique-destination"><img src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/home/wallonie/wallonie4.jpg" alt="Wallonie" class="aspect-[7/5] w-[37rem] max-w-none rounded-2xl bg-gray-50 object-cover"></a> 
                </div>
                <div class="contents lg:col-span-2 lg:col-end-2 lg:ml-auto lg:flex lg:w-[37rem] lg:items-start lg:justify-end lg:gap-x-8">
                <div class="order-first flex w-64 flex-none justify-end self-end lg:w-auto">
                    <a href="https://www.vacancesweb.be/liste-des-themes/wallonie-belgique-destination"><img src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/home/wallonie/wallonie2.jpg" alt="Wallonie" class="aspect-[4/3] w-[24rem] max-w-none flex-none rounded-2xl bg-gray-50 object-cover"></a> 
                </div>
                <div class="flex w-96 flex-auto justify-end lg:w-auto lg:flex-none">
                    <a href="https://www.vacancesweb.be/liste-des-themes/wallonie-belgique-destination"><img src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/home/wallonie/wallonie1.jpg" alt="Wallonie" class="aspect-[7/5] w-[37rem] max-w-none flex-none rounded-2xl bg-gray-50 object-cover"></a> 
                </div>
                <div class="hidden sm:block sm:w-0 sm:flex-auto lg:w-auto lg:flex-none">
                    <a href="https://www.vacancesweb.be/liste-des-themes/wallonie-belgique-destination"><img src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/home/wallonie/wallonie3.jpg" alt="Wallonie" class="aspect-[4/3] w-[24rem] max-w-none rounded-2xl bg-gray-50 object-cover"></a> 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <footer class="bg-gray-900 py-20">
        
        <div class="mx-auto w-full sm:max-w-7xl px-6 lg:px-8">
          <div class="grid grid-cols-1 items-center gap-x-8 gap-y-16 lg:grid-cols-2">
            <div class="mx-auto w-full sm:max-w-7xl ">
                <div class="relative isolate overflow-hidden bg-gray-900 px-6 py-24 shadow-2xl sm:rounded-3xl sm:px-24 xl:py-32">
                  <h2 class="mx-auto max-w-2xl text-center text-3xl font-bold tracking-tight text-white sm:text-4xl">Newsletter</h2>
                  <p class="mx-auto mt-2 max-w-xl text-center text-md leading-8 text-gray-300">En introduisant mon adresse e-mail, je confirme ma demande de recevoir les newsletters de Vacancesweb et de ses partenaires. Je consens dès lors d'un traitement de mes coordonnées à cette fin</p>
                  <form class="mx-auto mt-10 flex max-w-md gap-x-4">
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="min-w-0 flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-white sm:text-sm sm:leading-6" placeholder="Enter your email">
                    <button type="submit" class="flex-none rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Notify me</button>
                  </form>
                  <svg viewBox="0 0 1024 1024" class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2" aria-hidden="true">
                    <circle cx="512" cy="512" r="512" fill="url(#759c1415-0410-454c-8f7c-9a820de03641)" fill-opacity="0.7" />
                    <defs>
                      <radialGradient id="759c1415-0410-454c-8f7c-9a820de03641" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(512 512) rotate(90) scale(512)">
                        <stop stop-color="#0d99ff" />
                        <stop offset="1" stop-color="#001b8a" stop-opacity="0" />
                      </radialGradient>
                    </defs>
                  </svg>
                </div>
            </div>
            <div class="mx-auto grid w-full max-w-xl grid-cols-2 items-center gap-y-12 sm:gap-y-14 lg:mx-0 lg:max-w-none lg:pl-8">
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/immovlan.png" alt="immovlan" >
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/rossel.svg" alt="rossel" >
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/vlan-voyage.png" alt="vlan-voyage" >
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/lesoir.jpg" alt="lesoir" >
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/soirmag.jpg" alt="soirmag" >
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/7dimanche.jpg" alt="7dimanche" >
                <img class="max-h-12 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/sudinfo.jpg" alt="sudinfo" >
                <img class="max-h-16 w-full object-contain object-left" src="@include('components.icon.preload-image')" data-src="https://vacancesweb.s3.fr-par.scw.cloud/images/static/partners/cinetelerevue.png" alt="cinetelerevue" >
            </div>
          </div>
        </div>
        <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 lg:px-8">
            <nav class="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
              <div class="pb-6">
                <a href="#" class="text-sm leading-6 text-gray-400 hover:text-white">Contact</a>
              </div>
              <div class="pb-6">
                <a href="#" class="text-sm leading-6 text-gray-400 hover:text-white">FAQ</a>
              </div>
              <div class="pb-6">
                <a href="#" class="text-sm leading-6 text-gray-400 hover:text-white">Contracts</a>
              </div>
              <div class="pb-6">
                <a href="#" class="text-sm leading-6 text-gray-400 hover:text-white">A propos</a>
              </div>
              <div class="pb-6">
                <a href="#" class="text-sm leading-6 text-gray-400 hover:text-white">Jobs</a>
              </div>
            </nav>
            <div class="mt-10 flex justify-center space-x-10">
              <a href="https://www.facebook.com/Vacancesweb/" class="text-gray-400 hover:text-white">
                <span class="sr-only">Facebook</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
              </a>
              <a href="https://www.instagram.com/vacancesweb/" class="text-gray-400 hover:text-white">
                <span class="sr-only">Instagram</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                </svg>
              </a>
              <a href="https://twitter.com/vacancesweb_be" class="text-gray-400 hover:text-white">
                <span class="sr-only">Twitter</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                </svg>
              </a>
              <a href="https://www.pinterest.com/vacancesweb" class="text-gray-400 hover:text-white">
                <span class="sr-only">Pinterest</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 50 50" aria-hidden="true">
                    <path d="M25,2C12.3178711,2,2,12.3178711,2,25c0,9.8841553,6.2675781,18.3302612,15.036377,21.5769653	c-0.2525635-2.2515869-0.2129517-5.9390259,0.2037964-7.7243652c0.3902588-1.677002,2.5212402-10.6871338,2.5212402-10.6871338	s-0.6433105-1.2883301-0.6433105-3.1911011c0-2.9901733,1.7324219-5.2211914,3.8898315-5.2211914	c1.8349609,0,2.7197876,1.3776245,2.7197876,3.0281982c0,1.8457031-1.1734619,4.6026611-1.78125,7.1578369	c-0.506897,2.1409302,1.0733643,3.8865356,3.1836548,3.8865356c3.821228,0,6.7584839-4.0296021,6.7584839-9.8453369	c0-5.147583-3.697998-8.7471924-8.9795532-8.7471924c-6.1167603,0-9.7072754,4.588562-9.7072754,9.3309937	c0,1.8473511,0.7111816,3.8286743,1.6000977,4.9069824c0.175293,0.2133179,0.2009277,0.3994141,0.1488647,0.6160278	c-0.1629028,0.678894-0.5250854,2.1392822-0.5970459,2.4385986c-0.0934448,0.3944702-0.3117676,0.4763184-0.7186279,0.2869263	c-2.685791-1.2503052-4.364502-5.1756592-4.364502-8.3295898c0-6.7815552,4.9268188-13.0108032,14.206543-13.0108032	c7.4588623,0,13.2547607,5.3138428,13.2547607,12.4179077c0,7.4100342-4.6729126,13.3729858-11.1568604,13.3729858	c-2.178894,0-4.2263794-1.132019-4.9267578-2.4691772c0,0-1.0783081,4.1048584-1.3404541,5.1112061	c-0.4524536,1.7404175-2.3892822,5.3460083-3.3615723,6.9837036C20.1704712,47.6074829,22.5397949,48,25,48	c12.6826172,0,23-10.3173828,23-23C48,12.3178711,37.6826172,2,25,2z"/>
                </svg>
              </a>
              <a href="https://www.youtube.com/channel/UCYV-GxxtGxrFm3v2b9BACkQ" class="text-gray-400 hover:text-white">
                <span class="sr-only">YouTube</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                </svg>
              </a>
            </div>
            <p class="mt-10 text-center text-xs leading-5 text-white">&copy; {{date('Y')}} Immovlan S.A. Vacancesweb.be. All rights reserved.</p>
          </div>
          <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
            <div class="xl:grid xl:grid-cols-2 xl:gap-8">
              
              <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                  <div>
                    <h3 class="text-sm font-semibold leading-6 text-white">Répertoires</h3>
                    <ul role="list" class="mt-6 space-y-4">
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Locations de vacances</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Vacances</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Articles et reportages</a>
                      </li>
                    </ul>
                  </div>
                  <div class="mt-10 md:mt-0">
                    <h3 class="text-sm font-semibold leading-6 text-white">Légale</h3>
                    <ul role="list" class="mt-6 space-y-4">
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Conditions générales</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Partenaires</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Conditions d'utilisation</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Publication presse</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-8">
                  <div>
                    <h3 class="text-sm font-semibold leading-6 text-white">Company</h3>
                    <ul role="list" class="mt-6 space-y-4">
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">About</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Blog</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Jobs</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Press</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Partners</a>
                      </li>
                    </ul>
                  </div>
                  <div class="mt-10 md:mt-0">
                    <h3 class="text-sm font-semibold leading-6 text-white">Legal</h3>
                    <ul role="list" class="mt-6 space-y-4">
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Claim</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Privacy</a>
                      </li>
                      <li>
                        <a href="#" class="text-sm leading-6 text-gray-300 hover:text-white">Terms</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </footer>
  </div>
  