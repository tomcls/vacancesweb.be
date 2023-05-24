<div  x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="flex min-h-full flex-col">
    <header class="shrink-0 border-b border-gray-200 bg-white">
      <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
        <div class="flex items-center gap-x-8">
          <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-300">
            <span class="sr-only">View notifications</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
          </button>
          <a href="#" class="-m-1.5 p-1.5">
            <span class="sr-only">Your profile</span>
            <img class="h-8 w-8 rounded-full bg-gray-800" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
          </a>
        </div>
      </div>
    </header>
    <div class="border-b border-none pb-1 sm:flex sm:items-start sm:justify-between py-2">
        <div class="mt-3 sm:ml-1 sm:mt-0">
            <label for="mobile-search-location" class="sr-only">Search</label>
            <label for="desktop-search-location" class="sr-only">Search</label>
            <div class="flex rounded-md shadow-sm">
              <div class="relative flex-grow focus-within:z-10 pl-1 max-w-md">
                <x-input.location 
                    wire:model.debounce.450ms="locationSearch" 
                    wire:keyup.debounce.450ms="locationsResult"
                    id="serachLocation" 
                    name="serachLocation" 
                    wireModel="locationId" 
                    :rows="$locations" 
                    class="py-2 rounded-none rounded-l-lg border-r-none"
                    placeholder="Où partez-vous ? Référence ?" >
                    <x-slot:icon>
                      <x-icon.map class="flex-shrink-0 text-sky-600"/>
                    </x-slot:icon>
                </x-input.location>
              </div>
              <div x-data="{ isOpen: false }">
                <div class="relative">
                  <button  @click.inside="isOpen = true" type="button" class="relative w-52 cursor-default rounded-none bg-white py-2.5 border-l-none pl-3 pr-10 text-left text-gray-900 (shadow-sm) ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm sm:leading-5" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
                    <span class="block truncate">Location de vacances</span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                      <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                      </svg>
                    </span>
                  </button>
                  <ul x-show="isOpen" @click.outside="isOpen = false" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
                    @foreach ($types as $type)
                        <li class="text-gray-900 relative cursor-default select-none py-2 pl-4 pr-4" id="listbox-option-0" role="option">
                        <span class="font-normal block truncate">{{$type->name}}</span>
                        </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <button type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M2 3.75A.75.75 0 012.75 3h11.5a.75.75 0 010 1.5H2.75A.75.75 0 012 3.75zM2 7.5a.75.75 0 01.75-.75h6.365a.75.75 0 010 1.5H2.75A.75.75 0 012 7.5zM14 7a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02l-1.95-2.1v6.59a.75.75 0 01-1.5 0V9.66l-1.95 2.1a.75.75 0 11-1.1-1.02l3.25-3.5A.75.75 0 0114 7zM2 11.25a.75.75 0 01.75-.75H7A.75.75 0 017 12H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                </svg>
                Sort
                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
        </div>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <label for="mobile-search-location" class="sr-only">Search</label>
          <label for="desktop-search-location" class="sr-only">Search</label>
          <div class="flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10 pl-2 max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input type="text" name="mobile-search-location" id="mobile-search-location" class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:hidden" placeholder="Search">
                <input type="text" name="desktop-search-location" id="desktop-search-location" class="hidden w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:block" placeholder="Search candidates">
            </div>
            <button @click="open = !open" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              <x-icon.filters />
            </button>
          </div>
        </div>
    </div>
    <div class="mx-auto flex w-full  items-start gap-x-4 px-0 pt-0 sm:px-0 lg:px-2 ">
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div x-show="open" 
        @click="open = false" 
        style="display: none;"
        class="fixed inset-0"></div>

        <div x-show="open" 
        
              style="display: none;"
              class="fixed inset-0 overflow-hidden z-50 pointer-events-auto w-screen max-w-md">
          <div 
              class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
              <div x-show="open" 
                  style="display: none;" 
                  x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                  x-transition:enter-start="translate-x-full" 
                  x-transition:enter-end="translate-x-0" 
                  x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                  x-transition:leave-start="translate-x-0" 
                  x-transition:leave-end="translate-x-full"  
                  class="pointer-events-auto w-screen max-w-md">
                <div class="flex h-full flex-col overflow-hidden bg-white shadow-xl">
                  <div class="p-6">
                    <div class="flex items-start justify-between">
                      <h2 class="text-base font-semibold leading-6 text-gray-900" id="slide-over-title">Plus de filtres</h2>
                      <div class="ml-3 flex h-7 items-center">
                        <button @click="open = !open" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-indigo-500">
                          <span class="sr-only">Close panel</span>
                          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="border-b border-gray-200">
                    <div class="px-6">
                      <nav class="-mb-px flex space-x-6">
                        <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                        <a href="#" wire:click="$set('tab', 'amenities')" class="@if($tab =='amenities')  border-indigo-500 text-indigo-600 @else border-transparent  text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Amenities</a>
                        <a href="#" wire:click="$set('tab', 'classifications')" class="@if($tab =='classifications')  border-indigo-500 text-indigo-600 @else border-transparent  text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Conforts</a>
                        <a href="#" wire:click="$set('tab', 'comforts')" class="@if($tab =='comforts')  border-indigo-500 text-indigo-600 @else border-transparent  text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif  whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Services</a>
                      </nav>
                    </div>
                  </div>
                  <div class="flex-1  overflow-y-auto">
                    <div class="grid grid-cols-2 gap-2  mt-4 pl-2 @if($tab!='amenities') hidden @endif">
                        @foreach ($comforts as $amenity)
                            <div class="basis-1/4">
                                <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                            </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-2 gap-2  mt-4 pl-2 @if($tab!='classifications') hidden @endif">
                      @foreach ($classifications as $index => $amenity)
                        <div class="basis-1/4">
                            <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->amenity->id.'  '.$amenity->name}}" id="{{$amenity->amenity->code}}" />
                            <div class="max-w-fit">
                                <x-input.group   label="" for="value" >
                                    <x-input.text wire:model.lazy="houseClassifications.{{$amenity->amenity->id}}"  value="{{$houseClassifications[$amenity->amenity->id]??null}}" placeHolder="value" />
                                  </x-input.group>
                            </div>
                        </div>
                      @endforeach
                    </div>
                    <div class="grid grid-cols-2 gap-2  mt-4 pl-2 @if($tab!='comforts') hidden @endif">
                      @foreach ($services as $amenity)
                      <div class="basis-1/4">
                          <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                      </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <main class="flex-1">
            <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-4">
              @forelse ($rows as $row)
                <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
                  <div class="aspect-h-3 aspect-w-4 bg-gray-200 sm:aspect-none group-hover:opacity-75 h-52">
                    <img src="{{$row->cover->url('small')}}" alt="" class="h-full w-full object-cover object-center sm:h-full sm:w-full">
                  </div>
                  <div class="flex flex-1 flex-col space-y-2 p-4">
                    <h2 class="text-sm font-medium text-gray-900">
                      <a href="#">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$row->title}}
                      </a>
                    </h2>
                    {{-- <p class="text-sm text-gray-500">Get the full lineup of our Basic Tees. Have a fresh shirt all week, and an extra for laundry day.</p> --}}
                    <div class="flex flex-1 flex-row justify-between w-full">
                        <span class="text-sm italic text-gray-500 px-0 mx-0"><x-icon.map class="text-xs h-3 w-3 pr-1 mr-0 text-sky-500" /> Belgique</span>
                        <p class="text-sm italic text-gray-500"></p>
                        <p class="text-sm italic text-gray-500">{{$row->type_name}}</p>
                    </div>
                    <div class="flex flex-1 flex-row justify-between w-full">
                        <p class="text-sm italic text-gray-500">3 chambres</p>
                        <p class="text-sm italic text-gray-500">{{$row->number_people}} personnes</p>
                        <p class="text-sm italic text-gray-500">{{$row->acreage}} m2</p>
                    </div>
                    <div class="flex flex-1 flex-row justify-between w-full space-x-1">
                        <div class="flex flex-1 flex-col  text-left">
                          <p class="text-sm italic  text-gray-700">{{$row->min_nights}} nuits min.</p>
                          <p class="text-base font-medium text-gray-900">€{{$row->week_price}} / semaine</p>
                        </div>
                        <x-button.secondary class="rounded-md px-2 text-sky-500"><x-icon.mail class="text-sky-500"/></x-button.secondary>
                        <x-button.secondary class="rounded-md px-2"><x-icon.phone class="text-sky-500"/></x-button.secondary>
                    </div>
                  </div>
                </div>
              @empty
                  
              @endforelse
          
                <!-- More products... -->
              </div>
        </main>
        <aside class="sticky top-12 hidden  w-1/2 shrink-0 xl:block ">
        <!-- Right column area -->
            <div wire:ignore
            x-data="{
                map:null,
                longitude: {{$region->longitude??'null'}},
                initMap() {
                    let mrk = null;
                        map = new window.mapboxgl.Map({
                        container: 'mapbox-container', // container ID
                        style: 'mapbox://styles/vacancesweb/clfsck5g100bx01pctix56uyn', // style URL
                        center: [{{$region->longitude??4.35609}}, {{$region->latitude??50.84439}}], // starting position [lng, lat] -74.5, 40
                        zoom: 15 // starting zoom
                    }); 

                    map.on('load', () => {
                        
                    });
                    map.addControl(new window.mapboxgl.NavigationControl());

                }
            }"
            x-init="initMap();"
            id="mapbox-container"
                class=" relative h-[calc(100vh-7rem)] overflow-hidden rounded-xl rounded-l-none border border-dashed border-gray-400 opacity-75">
                
            </div>
        </aside>
    </div>
  </div>
  @push('css')
    @vite(['node_modules/mapbox-gl/dist/mapbox-gl.css'])
  @endpush
  @push('scripts')
    @vite(['resources/js/mapbox-draw.js','resources/js/mapbox.js'])
  @endpush