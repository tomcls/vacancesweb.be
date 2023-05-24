<div x-data="{ isOpen: false }">
    <label for="price" class="block text-sm font-medium text-gray-700">Destination {{$search}}</label>
    <div class="relative mt-1 rounded-md shadow-sm">
      <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <span class="text-gray-500 sm:text-sm"></span>
      </div>
      <input @click.inside="isOpen = true" wire:model="search" wire:keyup="searchResult" type="text" name="search_region" id="search_region" class="border py-4  block w-full rounded-md  pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm md:text-base" placeholder="Search a destination" />
      @if($showdiv)
      <div class="absolute left-1/2 z-10 mt-3 w-screen max-w-md -translate-x-1/2 transform px-2 sm:px-0">
        <div x-show="isOpen"
            @click.outside="isOpen = false"
            class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                @foreach($regions as $region)
                    <div class="relative grid gap-6 bg-white px-5 py-8 sm:gap-8 sm:px-8 sm:py-4">
                        <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                        <!-- Heroicon name: outline/lifebuoy -->
                        <svg class="h-6 w-6 flex-shrink-0 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796a3.765 3.765 0 00-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 01-1.388.88m2.268-2.268l4.138 3.448m0 0a9.027 9.027 0 01-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0l-3.448-4.138m3.448 4.138a9.014 9.014 0 01-9.424 0m5.976-4.138a3.765 3.765 0 01-2.528 0m0 0a3.736 3.736 0 01-1.388-.88 3.737 3.737 0 01-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 01-1.652-1.306 9.027 9.027 0 01-1.306-1.652m0 0l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.765 3.765 0 010-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 011.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 00-1.652 1.306A9.025 9.025 0 004.33 7.288" />
                        </svg>
                        <div class="ml-4">
                            <p class="text-base font-medium text-gray-900">{{ $region['name'] }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $region['country_name'] }}</p>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
  </div>