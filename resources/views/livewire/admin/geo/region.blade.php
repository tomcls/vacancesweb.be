
<div>
    <form wire:submit.prevent="save">
      <div class="space-y-10 sm:space-y-10">
        <div class="flex justify-between  ">
          <div class="justify-self-start">
            <h2 class="text-base font-semibold leading-7 text-gray-900 pt-10">Details</h2>
          </div>
          <div class=" justify-self-end pt-4 flex flex-row space-x-1">
            <div>
              <x-button.primary type="submit" class=" mt-3 " size="text-md">
                 Save
              </x-button.primary>
            </div>
            <div>
              <x-button.secondary wire:click="active" class=" mt-3 px-3 py-2.5 border-2 " color="{{$active?'bg-green-500 border-green-600':'bg-red-500 border-red-600'}}">
                <x-icon.active class=" text-white " />
              </x-button.secondary>
            </div>
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
          <div class="justify-self-start">
            <x-input.group  for="lang" label="Language" >
                <x-input.select wire:model="lang" id="lang" class=" py-2" wire:click="refreshContent">
                    <option value="{{App::currentLocale()}}" >{{App::currentLocale()}}</option>
                    @foreach (config('app.langs') as $lg)
                      @if($lg != App::currentLocale()) 
                          <option value="{{ $lg }}">{{$lg }}</option>
                      @endif
                    @endforeach
                </x-input.select>
              </x-input.group>
          </div>
          <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-3">
            <div class="justify-self-start basis-3/12  ">
              <x-input.group for="custom" label="Custom ?" :error="$errors->first('region.custom')">
                <x-input.checkbox wire:model="region.custom" id="custom"/>
              </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12  ">
              <x-input.group in for="country-search" label="Country" :error="$errors->first('region.country_id')">
                <x-input.autocomplete 
                    wire:model.debounce.450ms="countrySearch" 
                    wire:keyup.debounce.450ms="countriesResult"
                    id="country-search" 
                    name="country-search" 
                    wireModel="region.country_id" 
                    :rows="$countries" 
                    placeholder="Find a country" >
                    <x-slot:icon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                    </x-slot:icon>
                </x-input.autocomplete>
              </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12  ">
                <x-input.group  for="level" label="Level" :error="$errors->first('region.level')">
                    <x-input.select wire:model="region.level" id="level" class=" py-2" >
                        <option value="{{$region->level}}" >{{$region->level}}</option>
                        @foreach ($levels as $level)
                        @if($level != $region->level) 
                            <option value="{{ $level }}">{{$level }}</option>
                        @endif
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12">
                <x-input.group   label="Centroid longitude" for="longitude" :error="$errors->first('region.longitude')">
                    <x-input.text wire:model.debounce.150ms="region.longitude" id="longitude"  >
                    <x-slot:leadingIcon>
                        <x-icon.map class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                    </x-input.text>
                </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12">
                <x-input.group   label="Centroid latitude" for="latitude" :error="$errors->first('region.latitude')">
                    <x-input.text wire:model="region.latitude" id="latitude"  >
                      <x-slot:leadingIcon>
                          <x-icon.map class="flex-shrink-0 text-sky-500"/>
                      </x-slot:leadingIcon>
                    </x-input.text>
                </x-input.group>
            </div>
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
          <div class="justify-self-start">
            <label class=" text-sm font-medium leading-5 text-gray-700"></label>
          </div>
          <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-3">
            <div class="justify-self-start basis-3/12  ">
                    <x-input.group   label="North East Latitude" for="ne_lat" :error="$errors->first('region.ne_lat')">
                        <x-input.text wire:model.debounce.150ms="region.ne_lat" id="ne_lat"  >
                        <x-slot:leadingIcon>
                            <x-icon.map class="flex-shrink-0 text-sky-500"/>
                        </x-slot:leadingIcon>
                        </x-input.text>
                    </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12  ">
                <x-input.group   label="North East Longitude" for="ne_lon" :error="$errors->first('region.ne_lon')">
                    <x-input.text wire:model.debounce.150ms="region.ne_lon" id="ne_lon"  >
                    <x-slot:leadingIcon>
                        <x-icon.map class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                    </x-input.text>
                </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12">
                <x-input.group   label="South West latitude" for="sw_lat" :error="$errors->first('region.sw_lat')">
                    <x-input.text wire:model.debounce.150ms="region.sw_lat" id="sw_lat"  >
                    <x-slot:leadingIcon>
                        <x-icon.map class="flex-shrink-0 text-sky-500"/>
                    </x-slot:leadingIcon>
                    </x-input.text>
                </x-input.group>
            </div>
            <div class="justify-self-start basis-3/12">
                <x-input.group   label="South West longitude" for="sw_lon" :error="$errors->first('region.sw_lon')">
                    <x-input.text wire:model="region.sw_lon" id="sw_lon"  >
                      <x-slot:leadingIcon>
                          <x-icon.map class="flex-shrink-0 text-sky-500"/>
                      </x-slot:leadingIcon>
                    </x-input.text>
                </x-input.group>
            </div>
          </div>
        </div>
        <div>
          @foreach ($names as $key => $name)
              @livewire('admin.geo.region-translation',['regionName' => $name,'lang'=>$lang,'errors'=>$errors], key('name-'.$key))
          @endforeach
        </div>
        <div class="sm:grid sm:grid-cols-3 border-t-gray-500 sm:space-x-10">
          <div class="space-y-8" >
            <div class="mt-10">
              <label class="text-sm font-medium leading-5 text-gray-700">Location</label>
            </div>
            <div>
              <x-input.group for="region-search" label="Region">
                <x-input.autocomplete 
                    wire:model.debounce.450ms="regionSearch" 
                    wire:keyup.debounce.450ms="regionsResult"
                    id="region-search" 
                    name="region-search" 
                    wireModel="location" 
                    :rows="$regions" 
                    placeholder="Find a region" >
                    <x-slot:icon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                    </x-slot:icon>
                </x-input.autocomplete>
              </x-input.group>
            </div>
            <div>
            </div>
            <div>
              
            </div>
          </div>
          <div class="mt-10 col-span-2" >
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

                        let polygon  = {{($region->geom??'null')}};

                        map.on('load', () => {
                            
                        });

                        const draw = new MapboxDraw({
                            displayControlsDefault: false,
                            // Select which mapbox-gl-draw control buttons to add to the map.
                            controls: {
                                polygon: true,
                                trash: true
                            },
                            // Set mapbox-gl-draw to draw by default.
                            // The user does not have to click the polygon control button first.
                            defaultMode: 'draw_polygon'
                        });

                        map.addControl(draw); 
                        map.addControl(new window.mapboxgl.NavigationControl());

                        if(polygon) {
                            draw.deleteAll();
                            draw.add(polygon);
                        }
                        if(longitude) {
                          addMarker({{$region->longitude??'null'}}, {{$region->latitude??'null'}});
                          map.fitBounds([
                              [{{$region->sw_lon??'null'}}, {{$region->sw_lat??'null'}}], // southwestern corner of the bounds
                              [{{$region->ne_lon??'null'}}, {{$region->ne_lat??'null'}}] // northeastern corner of the bounds
                          ]);
                        }

                        Livewire.on('locationChanged', loc => {
                            if(mrk) {
                              mrk.remove();
                              console.log('remove marker')
                            }
                            addMarker(loc.lng,loc.lat);
                            if(polygon) {
                                draw.deleteAll() 
                            }
                            draw.add(JSON.parse(loc.geom));
                            map.fitBounds([
                              [loc.sw_lon, loc.sw_lat], // southwestern corner of the bounds
                              [loc.ne_lon,loc.ne_lat] // northeastern corner of the bounds
                            ]);
                        });
                        
                        function onDragEnd() {
                          Livewire.emit('onMarkerDragend',mrk.getLngLat());
                        }

                        function addMarker(lng,lat) {
                          console.log('add marker')
                          mrk = new window.mapboxgl.Marker({draggable: true})
                          .setLngLat([lng, lat])
                          .addTo(map);
                          mrk.on('dragend', onDragEnd);
                        }
                        map.on('draw.create', updateArea);
                        map.on('draw.delete', updateArea);
                        map.on('draw.update', updateArea);

                        function updateArea(e) {
                            const data = draw.getAll();
                            if (data.features.length > 0) {
                                Livewire.emit('setPolygon',data.features[0]);
                            }
                        }
                    }
                }"
                x-init="initMap();">
              <div class="w-full h-96" id="mapbox-container"  x-ref="mapContainer"></div>
            </div>
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-3 border-t-gray-500 sm:space-x-10">
            <div class="space-y-8" >
                <label class="text-sm font-medium leading-5 text-gray-700">Description</label>
                <x-input.group  for="type" label="Type" >
                    <x-input.select wire:model="type" id="type" class=" py-2" wire:click="refreshContent" >
                        <option value="{{$type}}" >{{$type}}</option>
                        @foreach ($types as $t)
                          @if($type != $t) 
                              <option value="{{ $t }}">{{$t }}</option>
                          @endif
                        @endforeach
                    </x-input.select>
                  </x-input.group>
            </div>
            <div class="col-span-2">
                @foreach ($descriptions as $keyLang => $langDescriptions)
                @foreach ($langDescriptions as $keyType => $typeDescription)
                  @livewire('admin.geo.region-description', ['regionDescription' => $typeDescription, 'lang' => $lang, 'type' => 'default', 'keyLang' => $keyLang, 'keyType' => $keyType],key('description-'.$keyLang.$keyType))
                @endforeach  
              @endforeach
            </div>
        </div>
      </div>
    </form>
</div>
@push('css')
  @vite(['node_modules/suneditor/dist/css/suneditor.min.css','node_modules/mapbox-gl/dist/mapbox-gl.css'])
@endpush
@push('scripts')
  @vite(['resources/js/suneditor.js','resources/js/mapbox-draw.js','resources/js/mapbox.js'])
@endpush

