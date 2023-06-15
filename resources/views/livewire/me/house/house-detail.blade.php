
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
              <x-button.secondary wire:click="active" class=" mt-3 px-3 py-2.5 border " color="{{$active?'bg-green-500 border-green-600':'bg-rose-500 border-rose-600 hover:bg-rose-800 hover:border-rose-800'}}">
                <x-icon.active class="text-white " />
              </x-button.secondary>
            </div>
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
          <div class="justify-self-start">
            <label class=" text-sm font-medium leading-5 text-gray-700"></label>
          </div>
          <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-3">
            <div class="flex flex-row justify-between space-x-2 basis-2/5">
              <div class="justify-self-start basis-2/5  ">
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
              <div class="justify-self-start basis-3/5">
                <x-input.group  for="type-id" label="Type" :error="$errors->first('house.house_type_id')">
                  <x-input.select wire:model="house.house_type_id" id="type-id" class=" py-2">
                      <option value="" >Select type...</option>
                      @foreach ($houseTypes as $value => $type)
                      <option value="{{ $type->id }}">{{'#'.$type->id.' '.$type->code }}</option>
                      @endforeach
                  </x-input.select>
                </x-input.group>
              </div>
            </div>
          </div>
          <div class="justify-self-start ">
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
            <div class="justify-self-start">
              <label class=" text-sm font-medium leading-5 text-gray-700">Contact</label>
            </div>
            <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-3">
              <div class="flex flex-row justify-between space-x-2 basis-3/5">
                <div class="justify-self-start basis-2/4">
                  <x-input.group  for="email" label="Email" :error="$errors->first('house.email')">
                      <x-input.text wire:model.lazy="house.email" id="email" />
                  </x-input.group>
                </div>
                <div class="justify-self-start basis-2/4  ">
                  <x-input.group  for="phone" label="Phone" :error="$errors->first('house.phone')">
                    <x-input.text wire:model.lazy="house.phone" id="phone" />
                  </x-input.group>
                </div>
              </div>
            </div>
            <div class="justify-self-start basis-1/4">
            </div>
          </div>
          <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
            <div class="justify-self-start">
              <label class=" text-sm font-medium leading-5 text-gray-700">Other</label>
            </div>
            <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-4">
              <div class="flex flex-row justify-between space-x-2 basis-3/5">
                <div class="justify-self-start basis-2/4">
                  <x-input.group  for="rooms" label="Number of rooms" :error="$errors->first('house.rooms')">
                      <x-input.text wire:model.lazy="house.rooms" id="rooms" />
                  </x-input.group>
                </div>
                <div class="justify-self-start basis-2/4  ">
                  <x-input.group  for="number_people" label="Number of people" :error="$errors->first('house.number_people')">
                    <x-input.text wire:model.lazy="house.number_people" id="number_people" />
                  </x-input.group>
                </div>
                <div class="justify-self-start basis-2/4  ">
                  <x-input.group  for="acreage" label="Acreage/surface m2" :error="$errors->first('house.acreage')">
                    <x-input.text wire:model.lazy="house.acreage" id="acreage" />
                  </x-input.group>
                </div>
              </div>
            </div>
            <div class="justify-self-start basis-1/4">
            </div>
          </div>
            <div class="justify-self-start basis-1/4">
            </div>
        </div>
        <div>
          @foreach ($titles as $key => $title)
              @livewire('me.house.house-title',['houseTitle' => $title,'lang'=>$lang,'errors'=>$errors],key('title-'.$key))
          @endforeach
        </div>
        <div class="sm:grid sm:grid-cols-3 border-t-gray-500 space-x-10">
          <div class="space-y-8" >
            <div class="mt-1">
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
              <x-input.group   label="Street" for="street" :error="$errors->first('house.street')">
                <x-input.text wire:model.debounce.150ms="house.street" id="street"  >
                  <x-slot:leadingIcon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                  </x-slot:leadingIcon>
                </x-input.text>
              </x-input.group>
            </div>
            <div class="flex flex-row space-x-2">
              <x-input.group  label="Number" for="street_number" :error="$errors->first('house.street_number')">
                <x-input.text wire:model.debounce.150ms="house.street_number" id="street_number"  >
                  <x-slot:leadingIcon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                  </x-slot:leadingIcon>
                </x-input.text>
              </x-input.group>

              <x-input.group   label="Box" for="street_box" :error="$errors->first('house.street_box')">
                <x-input.text wire:model.debounce.150ms="house.street_box" id="street_box"  >
                  <x-slot:leadingIcon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                  </x-slot:leadingIcon>
                </x-input.text>
              </x-input.group>
            </div>
            <div class="flex flex-row space-x-2">
              <x-input.group  label="Zip" for="zip" :error="$errors->first('house.zip')">
                <x-input.text wire:model.debounce.150ms="house.zip" id="zip"  >
                  <x-slot:leadingIcon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                  </x-slot:leadingIcon>
                </x-input.text>
              </x-input.group>
              
              <x-input.group   label="City" for="city" :error="$errors->first('house.city')">
                <x-input.text wire:model.debounce.150ms="house.city" id="city"  >
                  <x-slot:leadingIcon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                  </x-slot:leadingIcon>
                </x-input.text>
              </x-input.group>
            </div>
          </div>
          <div class="mt-10 col-span-3 sm:col-span-2" >
            <div wire:ignore
                x-data="{
                    map:null,
                    longitude: {{$house->longitude??'null'}},
                    initMap() {
                        let mrk = null;
                          map = new window.mapboxgl.Map({
                          container: 'mapbox-container', // container ID
                          style: 'mapbox://styles/vacancesweb/clfsck5g100bx01pctix56uyn', // style URL
                          center: [{{$house->longitude??4.35609}}, {{$house->latitude??50.84439}}], // starting position [lng, lat] -74.5, 40
                          zoom: 15 // starting zoom
                        }); 

                        map.addControl(new window.mapboxgl.NavigationControl());

                        @if ($house->longitude)
                        if(longitude) {
                          addMarker({{$house->longitude}}, {{$house->latitude}});
                        }
                        @endif

                        Livewire.on('locationChanged', loc => {
                          map.flyTo({center: [loc.lng, loc.lat],essential: true});
                            if(mrk) {
                              mrk.remove();
                            }
                            addMarker(loc.lng,loc.lat);
                        });
                        
                        function onDragEnd() {
                          Livewire.emit('onMarkerDragend',mrk.getLngLat());
                        }

                        function addMarker(lng,lat) {
                          mrk = new window.mapboxgl.Marker({draggable: true})
                          .setLngLat([lng, lat])
                          .addTo(map);
                          mrk.on('dragend', onDragEnd);
                        }
                    }
                }"
                x-init="initMap();">
              <div class="w-full h-96" id="mapbox-container"  x-ref="mapContainer"></div>
            </div>
          </div>
        </div>
        <div>
          @foreach ($descriptions as $key => $description)
              @livewire('me.house.house-description',['houseDescription' => $description,'lang'=>$lang],key('description-'.$key))
          @endforeach
        </div>
      </div>
    </form>
</div>

