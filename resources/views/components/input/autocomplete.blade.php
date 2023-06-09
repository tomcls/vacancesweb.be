
@props([
    'wireModel' => null,
    'rows' => null,
    'icon' => null,
])
<div>
<div x-data="{ isOpen: false }" >
    <div class="relative mt-1 rounded-md shadow-sm">
      <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <span class="text-gray-500 sm:text-sm"></span>
      </div>
      <input @click.inside="isOpen = true"
            {{$attributes}}
            type="text" 
            class="
                block 
                w-full 
                rounded-md
                border-0
                flex-1 
                form-input
                ring-1 
                ring-inset
                focus:ring-2 
                focus:ring-inset
                transition duration-150 
                ease-in-out 
                sm:text-sm 
                sm:leading-5"  />
      <input type="hidden" wire:model="{{$wireModel}}" />
      @if($rows)
      <div class="absolute left-1/2 z-10 mt-3 w-screen max-w-md -translate-x-1/2 transform px-2 sm:px-0">
        <div x-show="isOpen"
            @click.outside="isOpen = false"
            class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                @foreach($rows as $row)
                @if(isset($row->id))
                <div wire:click="$emit('selectAutoCompleteItem','{{$wireModel}}','{{'#'.$row->id.' '.$row->title}}','{{$row->id}}','{{$row->subtitle}}')" 
                x-on:click.prevent="isOpen = false;" 
                class="relative grid gap-6 bg-white px-5 py-8 sm:gap-8 sm:px-8 sm:py-4" >
                    <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                    <!-- Heroicon name: outline/lifebuoy -->
                        @if ($row->image)
                            <img src="{{$row->image}}" class="h-12 w-12 rounded" />
                        @else
                            {{$icon}}
                        @endif
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">{{$row->title}}</p>
                            <p class="mt-1 text-sm text-gray-500">{{$row->subtitle}}</p>
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