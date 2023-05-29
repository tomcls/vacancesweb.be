
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
                <x-input.group  for="type-id" label="Type" :error="$errors->first('holiday.holiday_type_id')" >
                  <x-input.select wire:model="holiday.holiday_type_id" id="type-id" class=" py-2">
                      <option value="" >Select type...</option>
                      @foreach ($holidayTypes as $value => $type)
                      <option value="{{ $type->id }}">{{'#'.$type->id.' '.$type->code }}</option>
                      @endforeach
                  </x-input.select>
                </x-input.group>
              </div>
            </div>
            <div class="justify-self-start basis-3/5">
              <x-input.group  for="user-search" label="User" :error="$errors->first('holiday.user_id')">
                  <x-input.autocomplete 
                      wire:model="userSearch" 
                      wire:keyup="usersResult"
                      id="user-search" 
                      name="user-search" 
                      wireModel="holiday.user_id"
                      :rows="$users" 
                      placeholder="Find a user" >
                      <x-slot:icon>
                        <x-icon.user class="flex-shrink-0 text-sky-500"/>
                      </x-slot:icon>
                  </x-input.autocomplete>
              </x-input.group>
            </div>
          </div>
          <div class="justify-self-start basis-1/4">
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
          <div class="justify-self-start">
            <label class=" text-sm font-medium leading-5 text-gray-700">Publication date</label>
          </div>
          <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-3">
            <div class="flex flex-row justify-between space-x-2 basis-3/5">
              <div class="justify-self-start basis-2/4  ">
                <x-input.group  for="startdate" label="Start date" :error="$errors->first('holiday.startdate')">
                  <x-input.date wire:model="holiday.startdate" id="startdate" />
                </x-input.group>
              </div>
              <div class="justify-self-start basis-2/4">
                <x-input.group  for="enddate" label="End date" :error="$errors->first('holiday.enddate')">
                    <x-input.date wire:model="holiday.enddate" id="enddate" />
                </x-input.group>
              </div>
            </div>
          </div>
          <div class="justify-self-start basis-1/4">
          </div>
        </div>
        <div class="sm:grid sm:grid-cols-5   sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5  ">
          <div class="justify-self-start">
            <label class=" text-sm font-medium leading-5 text-gray-700">Contact</label>
          </div>
          <div class="flex flex-col sm:flex-row justify-between sm:space-x-2 col-span-3 sm:col-span-5 lg:col-span-3">
            <div class="flex flex-row justify-between space-x-2 basis-3/5">
              <div class="justify-self-start basis-2/4">
                <x-input.group  for="email" label="Email" :error="$errors->first('holiday.email')">
                    <x-input.text wire:model.lazy="holiday.email" id="email" />
                </x-input.group>
              </div>
              <div class="justify-self-start basis-2/4  ">
                <x-input.group  for="phone" label="Phone" :error="$errors->first('holiday.phone')">
                  <x-input.text wire:model.lazy="holiday.phone" id="phone" />
                </x-input.group>
              </div>
            </div>
          </div>
          <div class="justify-self-start basis-1/4">
          </div>
        </div>
        <div>
          @foreach ($titles as $key => $title)
              @livewire('admin.holiday.holiday-title',['holidayTitle' => $title,'lang'=>$lang,'errors'=>$errors],key('title-'.$key))
          @endforeach
        </div>
        <div class="sm:grid sm:grid-cols-3 border-t-gray-500 space-x-10">
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
              <x-input.group   label="Longitude" for="longitude" :error="$errors->first('holiday.longitude')">
                <x-input.text wire:model.debounce.150ms="holiday.longitude" id="longitude" >
                  <x-slot:leadingIcon>
                      <x-icon.map class="flex-shrink-0 text-sky-500"/>
                  </x-slot:leadingIcon>
                </x-input.text>
              </x-input.group>
            </div>
            <div>
              <x-input.group   label="Latitude" for="latitude" :error="$errors->first('holiday.latitude')">
                <x-input.text wire:model="holiday.latitude" id="latitude"  >
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
                    longitude: {{$holiday->longitude??'null'}},
                    initMap() {
                        let mrk = null;
                          map = new window.mapboxgl.Map({
                          container: 'mapbox-container', // container ID
                          style: 'mapbox://styles/vacancesweb/clfsck5g100bx01pctix56uyn', // style URL
                          center: [{{$holiday->longitude??4.35609}}, {{$holiday->latitude??50.84439}}], // starting position [lng, lat] -74.5, 40
                          zoom: 15 // starting zoom
                        }); 

                        map.addControl(new window.mapboxgl.NavigationControl());

                        @if ($holiday->longitude)
                        if(longitude) {
                          addMarker({{$holiday->longitude}}, {{$holiday->latitude}});
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
              @livewire('admin.holiday.holiday-description',['holidayDescription' => $description,'lang'=>$lang],key('description-'.$key))
          @endforeach
        </div>
      </div>
    </form>
</div>

