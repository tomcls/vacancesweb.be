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
    @livewire('search',['searchByUri'=>$searchByUri],key('searchbar'))
    <div class="mx-auto flex w-full  items-start gap-x-4 px-0 pt-0 sm:px-0 lg:px-2 ">
      <!-- Notification Success or alert -->
        
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div x-show="open" 
        @click="open = false" 
        style="display: none;"
        class="fixed inset-0"></div>
        @livewire('more-filters',['searchByUri'=>$searchByUri],key('moreFilters'))
        <main class="flex-1" >
            <h1 class="mx-2 sm:mx-0 font-bold py-4 text-sky-600 pl-1"><span>{{$rows->total()}}</span> <span class=" text-gray-800">{{strtolower(($searchByUri['houseType']->name)??'locations' )}} de vacances à louer {{!empty($searchByUri['region']) || !empty($searchByUri['country'])?' - ':''}} {{$searchByUri['region']->name ?? $searchByUri['country']->name ?? null}}</span></h1>
            <div class="px-2 sm:px-0 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-4">
              @forelse ($rows as $row)
                <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
                  <div class="aspect-h-3 aspect-w-4 bg-gray-200 sm:aspect-none group-hover:opacity-75 h-52">
                    <img src="@include('components.icon.preload-image')" data-src="{{$row->cover->url('small')}}" alt="" class="h-full w-full object-cover object-center sm:h-full sm:w-full">
                  </div>
                  <div class="flex flex-1 flex-col space-y-2 p-4">
                    <h2 class="text-sm font-medium text-gray-900">
                      <a href="#">
                        <span aria-hidden="true" class=" inset-0"></span>
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
                        <x-button.secondary class="rounded-md px-2 text-sky-500" wire:click.prevent="$emit('openContactForm',{{$row->id}})"><x-icon.mail class="text-sky-500"/></x-button.secondary>
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
    @livewire('house-contact',[],key('house-contact'))
  </div>
  @push('css')
    @vite(['node_modules/mapbox-gl/dist/mapbox-gl.css','node_modules/pikaday/css/pikaday.css'])
  @endpush
  @push('scripts')
    @vite(['resources/js/mapbox-draw.js','resources/js/mapbox.js','resources/js/pickaday.js','resources/js/moment.js'])
  @endpush