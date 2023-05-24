<div x-data="{
    detailOpen: true,
    imagesOpen: false,
    priceOpen: false,
    documentsOpen: false
  }">
      <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select wire:change="openTab($event.target.value)" id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-pink-400 focus:ring-indigo-500">
          <option value="detail" selected>Details</option>
          <option value="images">Images</option>
          <option  value="amenities">Amenities</option>
          <option  value="seasons">Seasons</option>
          <option  value="costs">Costs</option>
          <option  value="reservations">Reservations</option>
          <option  value="highlights">Highlights</option>
          <option  value="publications">Publications</option>
          <option  value="transactions">Invoices</option>
          <option  value="documents">Documents</option>
        </select>
      </div>
      <div class="hidden sm:block" >
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex" aria-label="Tabs">
            <button wire:click="openTab('detail')" class="{{$tabs['detail']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}}  w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Details</button>
            <button wire:click="openTab('images')" class="{{$tabs['images']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Images</button>
            <button wire:click="openTab('amenities')"  class="{{$tabs['amenities']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Amenities</button>
            <button wire:click="openTab('seasons')"  class="{{$tabs['seasons']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Seasons</button>
            <button wire:click="openTab('costs')"  class="{{$tabs['costs']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Costs</button>
            <button wire:click="openTab('reservations')"  class="{{$tabs['reservations']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Reservations</button>
            <button wire:click="openTab('highlights')"  class="{{$tabs['highlights']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Highlights</button>
            <button wire:click="openTab('publications')"  class="{{$tabs['publications']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Publications</button>
            <button wire:click="openTab('transactions')"  class="{{$tabs['transactions']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Invoices</button>
            <button wire:click="openTab('documents')"  class="{{$tabs['documents']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Documents</button>
          </nav>
        </div>
      </div>
      <div class="{{$tabs['detail']?'':'hidden'}}">
        @if ($tabs['detail'])
          @livewire('admin.house.house-detail')
        @endif
      </div>
      <div class="{{$tabs['images']==1?'':'hidden'}}">
        @if($tabs['images'])
          @livewire('admin.house.house-images',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['amenities']?'':'hidden'}}">
        @if($tabs['amenities'])
          @livewire('admin.house.house-amenities',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['seasons']?'':'hidden'}}">
        @if($tabs['seasons'])
          @livewire('admin.house.house-seasons',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['costs']?'':'hidden'}}">
        @if($tabs['costs'])
          @livewire('admin.house.house-costs',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['reservations']?'':'hidden'}}">
        @if($tabs['reservations'])
          @livewire('admin.house.house-reservations',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['highlights']?'':'hidden'}}">
        @if($tabs['highlights'])
          @livewire('admin.house.house-highlights',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['publications']?'':'hidden'}}">
        @if($tabs['publications'])
          @livewire('admin.house.house-publications',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['transactions']?'':'hidden'}}">
        @if($tabs['transactions'])
          @livewire('admin.house.house-transactions',['houseId'=>$houseId])
        @endif
      </div>
      <div class="{{$tabs['documents']?'':'hidden'}}">
        @if($tabs['documents'])
          @livewire('admin.house.house-documents',['houseId'=>$houseId])
        @endif
      </div>
</div>
@push('css')
  @vite(['node_modules/pikaday/css/pikaday.css'])
@endpush
@push('scripts')
@vite(['resources/js/pickaday.js','resources/js/moment.js'])
@endpush