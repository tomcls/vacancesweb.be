<div x-init="loadImages();">
    <div class="container mx-auto max-w-7xl pt-10 ">
        <h1 class="text-center text-3xl font-bold" >Découvrez nos offres Privilège lecteurs et abonnés.</h1>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
            <select onchange="(this.options[this.selectedIndex].value?  window.open(this.options[this.selectedIndex].value):'')" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
              <option value="{{route('partner',['slug'=>$slug])}}" >Catalog</option>
              <option value="{{route('partnerHolidays',['slug'=>$slug,'type'=>'circuit'])}}" >Circuits</option>
              <option value="{{route('partnerHolidays',['slug'=>$slug,'type'=>'citytrip'])}}">Séjours</option>
              <option value="{{route('partnerHolidays',['slug'=>$slug,'type'=>'club'])}}">Clubs</option>
              <option value="{{route('partnerHolidays',['slug'=>$slug,'type'=>'cruise'])}}">Croisières</option>
              <option value="{{route('partnerHolidays',['slug'=>$slug,'type'=>'hotel'])}}">Hôtels</option>
            </select>
          </div>
          <div class="hidden sm:inline-block w-full pt-10">
            <nav class="flex space-x-4 justify-center" aria-label="Tabs">
              <!-- Current: "bg-indigo-100 text-indigo-700", Default: "text-gray-500 hover:text-gray-700" -->
              <a href="{{route('partner',['slug'=>$slug])}}" class="bg-blue-100 text-blue-700 rounded-md px-3 py-2 text-sm font-medium"  aria-current="page">Catalogue</a>
              <a href="{{route('partnerHolidays',['slug'=>$slug,'type'=>'circuit'])}}" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium">Circuits</a>
              <a href="{{route('partnerHolidays',['slug'=>$slug,'type'=>'citytrip'])}}" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium">Séjours</a>
              <a href="{{route('partnerHolidays',['slug'=>$slug,'type'=>'club'])}}" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium">Clubs</a>
              <a href="{{route('partnerHolidays',['slug'=>$slug,'type'=>'cruise'])}}" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium">Croisières</a>
              <a href="{{route('partnerHolidays',['slug'=>$slug,'type'=>'hotel'])}}" class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-2 text-sm font-medium">Hotels</a>
            </nav>
          </div>
          <div class="w-full pt-5 text-center">
            <a href="http://vacancesweb.local/login" class="text-sm font-semibold leading-6 text-left text-sky-800"><span class="text-pink-500 text-xl" aria-hidden="true">→</span> Ce qu'en penses nos lecteurs </a>
          </div>
          @if ($post)
          <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white mt-5">
            <div class="aspect-h-4 aspect-w-3 bg-gray-200 sm:aspect-none group-hover:opacity-75 sm:h-96">
              <img src="{{$hero->image ? $hero->url('large') : $post->cover }}" alt="Eight shirts arranged on table in black, olive, grey, blue, white, red, mustard, and green." class="h-full w-full object-cover object-center sm:h-full sm:w-full">
            </div>
            <div class="flex flex-1 flex-col space-y-2 p-4">
                <h3 class="text-xl font-bold inline-block pt-3 uppercase pb-1"> <x-icon.guide class=" text-pink-500 w-7 h-7"/> Reportage</h3>
                <a href="{{route('article',['slug'=>$post->slug])}}">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    <h2 class="text-xl font-bold inline-block py-1">{{$post->title}}</h2>
                </a>
                <p class="text-md text-gray-500 font-light">{{$post->subtitle}}</p>
            </div>
          </div>
          @else
          <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white mt-5">
            <div class="aspect-h-4 aspect-w-3 bg-gray-200 sm:aspect-none group-hover:opacity-75 sm:h-96">
              <img src="{{$hero->image ? $hero->url('large') : $holiday->holiday->cover->url('large')}}" alt="Eight shirts arranged on table in black, olive, grey, blue, white, red, mustard, and green." class="h-full w-full object-cover object-center sm:h-full sm:w-full">
            </div>
            <div class="flex flex-1 flex-col space-y-2 p-4">
                <h3 class="text-xl font-bold inline-block pt-3 uppercase pb-1"> <x-icon.guide class=" text-pink-500 w-7 h-7"/> {{$holiday->holiday->holidayType->translation->first()->name}}</h3>
                <a href="#">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    <h2 class="text-xl font-bold inline-block py-1">{{$holiday->name}}</h2>
                </a>
                <p class="text-md text-gray-500 font-light">€{{$holiday->holiday->lowestPrice->price_customer}}</p>
            </div>
          </div>
          @endif
          
          @if (count($catalog))
            <h2 class="text-center text-3xl font-bold my-10" >Nouveaux voyages dans le catalogue.</h2>

            <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-8">
                @forelse ($catalog as $row)
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
                <!-- More products... -->
            </div>
          @else
            <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-8">
                @forelse ($holidays as $row)
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
            <!-- More products... -->
          </div>
          @endif
          
          @livewire('partner.related', ['related' => $posts ?? $posts], key('relatedPosts'))
    </div>
</div>
