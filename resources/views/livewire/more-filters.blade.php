
<div x-show="open" 
    style="display: none;"
    class="fixed inset-0 overflow-hidden z-50 pointer-events-auto w-screen max-w-md">
    <div class="absolute inset-0 overflow-hidden">
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
            <div x-show="open" 
                style="display: none;" 
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                x-transition:enter-start="translate-x-full" 
                x-transition:enter-end="translate-x-0" 
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                x-transition:leave-start="translate-x-0" 
                x-transition:leave-end="translate-x-full"  
                class="pointer-events-auto w-screen max-w-md">
                <div class="flex h-full flex-col overflow-hidden bg-white shadow-xl">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-base font-semibold leading-6 text-gray-900" id="slide-over-title">Plus de filtres</h2>
                            <div class="ml-3 flex h-7 items-center">
                                <button @click="open = !open" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-sky-500">
                                <span class="sr-only">Close panel</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200">
                        <div class="px-6">
                            <nav class="-mb-px flex space-x-6">
                                <!-- Current: "border-sky-500 text-sky-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                                <a href="#" wire:click="$set('tab', 'types')" class="@if($tab =='types')  border-sky-500 text-sky-600 @else border-transparent  text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Types de bien</a>
                                <a href="#" wire:click="$set('tab', 'amenities')" class="@if($tab =='amenities')  border-sky-500 text-sky-600 @else border-transparent  text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Amenities</a>
                                <a href="#" wire:click="$set('tab', 'comforts')" class="@if($tab =='comforts')  border-sky-500 text-sky-600 @else border-transparent  text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif  whitespace-nowrap border-b-2 px-1 pb-4 text-sm font-medium">Services</a>
                            </nav>
                        </div> 
                    </div>
                    <div class="flex-1  overflow-y-auto">
                        <div class="grid grid-cols-2 gap-2  mt-4 pl-2 @if($tab!='types') hidden @endif">
                            @foreach ($types as $type)
                                <div class="basis-1/4">
                                    <x-input.checkbox wire:model="houseTypes"  value="{{$type->house_type_id}}" label="{{$type->name}}" id="type-{{$type->house_type_id}}" />
                                </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-2 gap-2  mt-4 pl-2 @if($tab!='amenities') hidden @endif">
                            @foreach ($comforts as $amenity)
                                <div class="basis-1/4">
                                    <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                                </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-2 gap-2  mt-4 pl-2 @if($tab!='comforts') hidden @endif">
                            @foreach ($services as $amenity)
                            <div class="basis-1/4">
                                <x-input.checkbox wire:model="houseAmenities"  value="{{$amenity->amenity->id}}" label="{{$amenity->name}}" id="{{$amenity->amenity->code}}" />
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="border-b border-gray-200 pb-5">
                        <div class="px-6 text-center">
                            <x-button.primary wire:click='search' >Search</x-button.primary>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
