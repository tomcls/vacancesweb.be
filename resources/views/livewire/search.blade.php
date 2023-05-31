<div>
    <div class="border-b border-none pb-1 sm:flex sm:items-start sm:justify-between py-2">
        <div class="mt-3 sm:ml-1 sm:mt-0">
            <label for="mobile-search-location" class="sr-only">Search</label>
            <label for="desktop-search-location" class="sr-only">Search</label>
            <div class="flex rounded-md shadow-sm">
              <div class="relative flex-grow focus-within:z-10 pl-1 max-w-md">
                <x-input.location 
                    
                    wire:model.debounce.450ms="locationSearch" 
                    wire:keyup.debounce.450ms="locationsResult"
                    id="serachLocation" 
                    name="serachLocation" 
                    wireModel="locationId" 
                    :rows="$locations" 
                    class="py-2 rounded-none rounded-l-lg border-r-none"
                    placeholder="Où partez-vous ? Référence ?" >
                    <x-slot:icon>
                      <x-icon.map class="flex-shrink-0 text-sky-600"/>
                    </x-slot:icon>
                </x-input.location>
              </div>
              <div x-data="{ isOpen: false }">
                <div class="relative">
                  <button  @click.inside="isOpen = true" type="button" class="relative w-52 cursor-default rounded-none bg-white py-2.5 border-l-none pl-3 pr-10 text-left text-gray-900 (shadow-sm) ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm sm:leading-5" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
                    <span class="block truncate">Location de vacances</span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                      <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
                      </svg>
                    </span>
                  </button>
                  <ul x-show="isOpen" @click.outside="isOpen = false" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
                    @foreach ($types as $type)
                        <li class="text-gray-900 relative cursor-default select-none py-2 pl-4 pr-4" id="listbox-option-0" role="option">
                        <span class="font-normal block truncate">{{$type->name}}</span>
                        </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <button type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M2 3.75A.75.75 0 012.75 3h11.5a.75.75 0 010 1.5H2.75A.75.75 0 012 3.75zM2 7.5a.75.75 0 01.75-.75h6.365a.75.75 0 010 1.5H2.75A.75.75 0 012 7.5zM14 7a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02l-1.95-2.1v6.59a.75.75 0 01-1.5 0V9.66l-1.95 2.1a.75.75 0 11-1.1-1.02l3.25-3.5A.75.75 0 0114 7zM2 11.25a.75.75 0 01.75-.75H7A.75.75 0 017 12H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                </svg>
                Sort
                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
        </div>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <label for="mobile-search-location" class="sr-only">Search</label>
          <label for="desktop-search-location" class="sr-only">Search</label>
          <div class="flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10 pl-2 max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input type="text" name="mobile-search-location" id="mobile-search-location" class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:hidden" placeholder="Search">
                <input type="text" name="desktop-search-location" id="desktop-search-location" class="hidden w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:block" placeholder="Search candidates">
            </div>
            <button @click="open = !open" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              <x-icon.filters />
            </button>
          </div>
        </div>
    </div>
</div>
