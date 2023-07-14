<div x-init="loadImages(); Livewire.on('loadImages', () => {
    loadImages();
})">
    <div class="container mx-auto max-w-7xl pt-10 ">
        <h1 class="text-center text-3xl font-bold" >Découvrez nos offres Privilège lecteurs et abonnés.</h1>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
            <select id="type" name="type" wire:model="type" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
              <option value="circuit" >Circuits</option>
              <option value="citytrip">Séjours</option>
              <option value="club">Clubs</option>
              <option value="cruise">Croisières</option>
              <option value="hotel">Hôtels</option>
            </select>
          </div>
          <div class="hidden sm:inline-block w-full pt-10">
            <nav class="flex space-x-4 justify-center" aria-label="Tabs">
              <!-- Current: "bg-indigo-100 text-indigo-700", Default: "text-gray-500 hover:text-gray-700" -->
              <a href="{{route('partner',['slug'=>$slug])}}"  class="{{$type =='catalog' ? 'bg-blue-100 text-blue-700 ' : 'text-gray-500 hover:text-gray-700'}} rounded-md px-3 py-2 text-sm font-medium">Catalogue</a>
              <a href=""  wire:click.prevent="$set('type', 'circuit')" class="{{$type =='circuit' ? 'bg-blue-100 text-blue-700 ' : 'text-gray-500 hover:text-gray-700'}} rounded-md px-3 py-2 text-sm font-medium">Circuits</a>
              <a href=""  wire:click.prevent="$set('type', 'citytrip')" class="{{$type =='citytrip' ? 'bg-blue-100 text-blue-700 ' : 'text-gray-500 hover:text-gray-700'}} rounded-md px-3 py-2 text-sm font-medium">Séjours</a>
              <a href=""  wire:click.prevent="$set('type', 'club')" class="{{$type =='club' ? 'bg-blue-100 text-blue-700 ' : 'text-gray-500 hover:text-gray-700'}} rounded-md px-3 py-2 text-sm font-medium">Clubs</a>
              <a href=""  wire:click.prevent="$set('type', 'cruise')" class="{{$type =='cruise' ? 'bg-blue-100 text-blue-700 ' : 'text-gray-500 hover:text-gray-700'}} rounded-md px-3 py-2 text-sm font-medium">Croisières</a>
              <a href=""  wire:click.prevent="$set('type', 'hotel')"class="{{$type =='hotel' ? 'bg-blue-100 text-blue-700 ' : 'text-gray-500 hover:text-gray-700'}} rounded-md px-3 py-2 text-sm font-medium">Hotels</a>
            </nav>
          </div>
          <div class="w-full pt-5 text-center">
            <a href="http://vacancesweb.local/login" class="text-sm font-semibold leading-6 text-left text-sky-800"><span class="text-pink-500 text-xl" aria-hidden="true">→</span> Ce qu'en penses nos lecteurs </a>
          </div>
          
            <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-8 mt-8">
                @forelse ($rows as $row)
                <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <div class="aspect-h-4 aspect-w-3 bg-gray-200 sm:aspect-none group-hover:opacity-75 sm:h-96">
                    <img data-src="{{$row->holiday->cover->url('small')}}" alt="Eight shirts arranged on table in black, olive, grey, blue, white, red, mustard, and green." class="h-full w-full object-cover object-center sm:h-full sm:w-full">
                    </div>
                    <div class="flex flex-1 flex-col space-y-2 p-4">
                    <h3 class="text-sm font-medium text-gray-900">
                        <a href="#">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$row->title}}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500"> <x-icon.map class="text-sky-600" /> {{$row->country_name}} - {{$row->region_name}}</p>
                    <div class="flex flex-1 flex-col justify-end">
                        <p class="text-sm italic text-gray-500">Départ le {{$row->holiday->lowestPrice->departure_date}}</p>
                        <p class="text-base font-medium text-gray-900">€{{$row->holiday->lowestPrice->price_customer}}</p>
                    </div>
                    </div>
                </div>
                @empty
                    
                @endforelse

                <div>
                    {{ $rows->links() }}
                </div>
            </div>
          
          @livewire('partner.related', ['related' => $posts ?? $posts], key('relatedPosts'))
    </div>
</div>
