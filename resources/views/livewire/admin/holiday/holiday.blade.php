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
          <option  value="prices">Pricess</option>
          <option  value="transactions">Transactions</option>
          <option  value="documents">Documents</option>
        </select>
      </div>
      <div class="hidden sm:block" >
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex" aria-label="Tabs">
            <button wire:click="openTab('detail')" class="{{$tabs['detail']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}}  w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Details</button>
            <button wire:click="openTab('images')" class="{{$tabs['images']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Images</button>
            <button wire:click="openTab('prices')"  class="{{$tabs['prices']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Prices</button>
            <button wire:click="openTab('transactions')" class="{{$tabs['transactions']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Transactions</button>
            <button wire:click="openTab('documents')" class="{{$tabs['documents']?'border-pink-400 text-sky-500':'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'}} w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium">Documents</button>
          </nav>
        </div>
      </div>
      <div class="{{$tabs['detail']?'':'hidden'}}">
        @livewire('admin.holiday.holiday-detail',['holidayId'=>$holidayId])
      </div>
      <div class="{{$tabs['images']?'':'hidden'}}">
        @if($tabs['images'])
          @livewire('admin.holiday.holiday-images',['holidayId'=>$holidayId])
        @endif
      </div>
      <div class="{{$tabs['prices']?'':'hidden'}}">
        @if($tabs['prices'])
          @livewire('admin.holiday.holiday-prices',['holidayId'=>$holidayId])
        @endif
      </div>
      <div class="{{$tabs['transactions']?'':'hidden'}}">
        @if($tabs['transactions'])
          @livewire('admin.holiday.holiday-transactions',['holidayId'=>$holidayId])
        @endif
      </div>
      <div class="{{$tabs['documents']?'':'hidden'}}">
        @if($tabs['documents'])
          @livewire('admin.holiday.holiday-documents',['holidayId'=>$holidayId])
        @endif
      </div>
</div>
@push('css')
  @vite(['node_modules/pikaday/css/pikaday.css','node_modules/suneditor/dist/css/suneditor.min.css','node_modules/mapbox-gl/dist/mapbox-gl.css','node_modules/filepond/dist/filepond.min.css','node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css'])
@endpush
@push('scripts')
  @vite(['resources/js/suneditor.js','resources/js/mapbox.js','resources/js/filepond.js','resources/js/pickaday.js'])
@endpush
