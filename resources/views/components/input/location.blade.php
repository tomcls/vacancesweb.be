
@props([
    'wireModel' => null,
    'rows' => null,
    'icon' => null,
])
<div>
<div x-data="{ isOpen: false }" >
    <div class="relative  rounded-md shadow-sm">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <x-icon.search class="text-blue-600" />
        </div>
        <input @click.inside="isOpen = true"
            {{ $attributes->merge(['class' => 'block w-full rounded-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6'  ])}}
            type="text" 
            placeholder="Find a location">
      <input type="hidden" wire:model="{{$wireModel}}" />
      @if($rows)
      <!--origin-top-right absolute left-0 sm:right-0 bottom-11 sm:-bottom-32   mt-2 -mr-1  rounded-md shadow-lg z-20-->
      <div :class="{'bottom-11': window.mobileCheck() === true}" class="absolute left-0 z-10 mt-3 w-screen max-w-md transform px-2 sm:px-0 ">
        <div x-show="isOpen"
            @click.outside="isOpen = false"
            class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                @foreach($rows as $row)
                @if(isset($row['id']))
                <div wire:click="$emit('selectAutoCompleteItem','{{$wireModel}}','{{'#'.$row['id'].' '.$row['title']}}','{{$row['id']}}','{{$row['type']}}')" 
                x-on:click.prevent="isOpen = false;" 
                class="relative grid gap-6 bg-white pl-2 py-8 sm:gap-8  sm:py-4" >
                    <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                    <!-- Heroicon name: outline/lifebuoy -->
                        @if (isset($row['image']))
                            <img src="/houses/images/{{$row['id']}}/small_{{$row['image']}}" class="h-12 w-12 rounded" />
                        @else
                            {{$icon}}
                        @endif
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">{{$row['title']}}</p>
                            <p class="mt-1 text-sm text-gray-500">{{$row['subtitle']}}</p>
                        </div>
                    </a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
  </div>
</div>