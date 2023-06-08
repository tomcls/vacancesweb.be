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
                    class="py-2 rounded sm:rounded-none sm:rounded-l-lg border-r-none ml-2 sm:ml-0"
                    placeholder="Où partez-vous ? Référence ?" >
                    <x-slot:icon>
                      <x-icon.map class="flex-shrink-0 text-sky-600"/>
                    </x-slot:icon>
                </x-input.location>
              </div>
              <x-search.date  wire:model='dateFrom' placeHolder="Arrivé"  />
              <x-search.date  wire:model='dateTo' placeHolder="Départ" />
              <div class="hidden sm:block">
                <div class="relative  rounded-none shadow-sm border-l-0 border-r-0">
                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 border-l-0">
                    <x-icon.users class="h-5 w-5 text-blue-400" />
                  </div>
                  <input wire:model.lazy="numberPeople" class="block w-full border-l-0 border-r-0 rounded-none rounded-r-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Nombre de vacanciers">
                </div>
              </div>
            </div>
        </div>
        <div class="mt-3 sm:ml-4 sm:mt-0 flex flex-row">

          <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left z-10">
            <div>
                <span class="shadow-sm">
                    <button @click="open = !open" type="button" class="rounded-l ml-2 sm:ml-0  relative  inline-flex items-center gap-x-1.5  px-3 py-2.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
                        <x-icon.sort />
                          Trier par
                        <x-icon.ddown />
                    </button>
                </span>
            </div>
        
            <div x-show="open" 
                style="display: none;" 
                x-description="Dropdown panel, show/hide based on dropdown state." 
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="transform opacity-0 scale-95" 
                x-transition:enter-end="transform opacity-100 scale-100" 
                x-transition:leave="transition ease-in duration-75" 
                x-transition:leave-start="transform opacity-100 scale-100" 
                x-transition:leave-end="transform opacity-0 scale-95" 
                class="origin-top-right absolute right-0 mt-2  rounded-md shadow-lg ">
                <div class="rounded-md bg-white shadow-xs">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                      <button @click="open = false" wire:click="$emit('setOrder',{'field':'id', 'direction':'desc'})" class="text-left block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        <x-icon.new /> Récent
                      </button>
                      <button @click="open = false" wire:click="$emit('setOrder',{'field':'week_price', 'direction':'asc'})" class="text-left block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        <x-icon.arrowup /> Prix asc
                      </button>
                      <button @click="open = false" wire:click="$emit('setOrder',{'field':'week_price', 'direction':'desc'})" class="text-left block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        <x-icon.arrowdown /> Prix desc
                      </button>
                    </div>
                </div>
            </div>
          </div>
          <div class="flex rounded-md shadow-sm mr-2">
            <button @click="open = !open" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              <span class="font-bold text-gray-500"> Plus de filtres </span> <x-icon.filters />
            </button>
          </div>
        </div>
    </div>
</div>
