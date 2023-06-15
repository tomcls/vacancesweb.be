<div x-data="{
    detailOpen: true,
    imagesOpen: false,
    priceOpen: false,
    documentsOpen: false
  }">
      <div class="sm:hidden">
        <label for="tab" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select wire:change="openTab($event.target.value)" id="tab" name="tab" class="block w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-indigo-500">
          <option value="detail" selected>Details</option>
          <option value="images">Images</option>
          <option  value="amenities">Amenities</option>
          <option  value="seasons">Seasons</option>
          <option  value="costs">Costs</option>
          <option  value="reservations">Reservations</option>
        </select>
      </div>
      <div class="hidden sm:block" >
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex" aria-label="Tabs">
            <button wire:click="openTab('detail')" class="{{$tab == 'detail' ? 'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}}  w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Details</button>
            <button wire:click="openTab('images')" class="{{$tab == 'images' ?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Images</button>
            <button wire:click="openTab('amenities')"  class="{{$tab == 'amenities'?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Amenities</button>
            <button wire:click="openTab('seasons')"  class="{{$tab == 'seasons' ?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Seasons</button>
            <button wire:click="openTab('costs')"  class="{{$tab == 'costs' ?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Costs</button>
            <button wire:click="openTab('reservations')"  class="{{$tab == 'reservations' ?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Reservations</button>
            </nav>
        </div>
      </div>
      <div class="{{$tab == 'detail' ? '': 'hidden'}}">
        @if ($tab == 'detail')
          @livewire('me.house.house-detail',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tab == 'images' ? '': 'hidden'}}">
        @if($tab == 'images')
          @livewire('me.house.house-images',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tab == 'amenities' ?'': 'hidden'}}">
        @if($tab == 'amenities')
          @livewire('me.house.house-amenities',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tab == 'seasons' ? '' : 'hidden'}}">
        @if($tab == 'seasons')
          @livewire('me.house.house-seasons',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tab == 'costs' ? '' : 'hidden'}}">
        @if($tab == 'costs')
          @livewire('me.house.house-costs',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tab == 'reservations' ? '' : 'hidden'}}">
        @if($tab == 'reservations')
          @livewire('me.house.house-reservations',['houseId'=>$houseId])
        @endif
      </div>
</div>
@push('css')
  @vite(['node_modules/pikaday/css/pikaday.css','node_modules/suneditor/dist/css/suneditor.min.css','node_modules/mapbox-gl/dist/mapbox-gl.css','node_modules/filepond/dist/filepond.min.css','node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css'])
@endpush
@push('scripts')
  @vite(['resources/js/suneditor.js','resources/js/mapbox.js','resources/js/filepond.js','resources/js/moment.js','resources/js/pickaday.js'])
@endpush