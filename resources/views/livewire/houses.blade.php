<div x-data="{
        open: false,
        sidebarOpen:false,
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
      x-init="
        loadImages();
        Livewire.on('loadImages', () => {
          loadImages();
      })" 
      @keydown.window.escape="open = false" @click.away="open = false" class="flex min-h-full flex-col">
    <x-layouts.menu position="relative" background="bg-white" textColor="text-black" />
    @livewire('search',[],key('searchbar'))
    <div class="mx-auto flex w-full  items-start gap-x-4 px-0 pt-0 sm:px-0 lg:px-2 ">
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div x-show="open" 
        @click="open = false" 
        style="display: none;"
        class="fixed inset-0"></div>
        <div x-show="open" 
              style="display: none;"
              class="fixed inset-0 overflow-hidden z-50 pointer-events-auto w-screen max-w-md">
          <div class="absolute inset-0 overflow-hidden">
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
        <main class="flex-1" >
            <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-4">
              @forelse ($rows as $row)
                <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
                  <div class="aspect-h-3 aspect-w-4 bg-gray-200 sm:aspect-none group-hover:opacity-75 h-52">
                    <img src="@include('components.icon.preload-image')" data-src="{{$row->cover->url('small')}}" alt="" class="h-full w-full object-cover object-center sm:h-full sm:w-full">
                  </div>
                  <div class="flex flex-1 flex-col space-y-2 p-4">
                    <h2 class="text-sm font-medium text-gray-900">
                      <a href="#">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$row->id}} {{$row->title}}
                      </a>
                    </h2>
                    {{-- <p class="text-sm text-gray-500">Get the full lineup of our Basic Tees. Have a fresh shirt all week, and an extra for laundry day.</p> --}}
                    <div class="flex flex-1 flex-row justify-between w-full">
                        <span class="text-sm italic text-gray-500 px-0 mx-0"><x-icon.map class="text-xs h-3 w-3 pr-1 mr-0 text-sky-500" /> {{$row->region_name}}</span>
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