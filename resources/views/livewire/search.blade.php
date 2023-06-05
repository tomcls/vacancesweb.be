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
              <x-search.date  wire:model='dateFrom' placeHolder="Arrivé" />
              <x-search.date  wire:model='dateTo' placeHolder="Départ" />
              <div >
                <div class="relative  rounded-none shadow-sm border-l-0 border-r-0">
                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 border-l-0">
                    <x-icon.users class="h-5 w-5 text-blue-400" />
                  </div>
                  <input wire:model.lazy="numberPeople" class="block w-full border-l-0 border-r-0 rounded-none border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Nombre de vacanciers">
                </div>
              </div>
              <button type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M2 3.75A.75.75 0 012.75 3h11.5a.75.75 0 010 1.5H2.75A.75.75 0 012 3.75zM2 7.5a.75.75 0 01.75-.75h6.365a.75.75 0 010 1.5H2.75A.75.75 0 012 7.5zM14 7a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02l-1.95-2.1v6.59a.75.75 0 01-1.5 0V9.66l-1.95 2.1a.75.75 0 11-1.1-1.02l3.25-3.5A.75.75 0 0114 7zM2 11.25a.75.75 0 01.75-.75H7A.75.75 0 017 12H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                </svg>
                Trier par
                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
        </div>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <div class="flex rounded-md shadow-sm">
            <button @click="open = !open" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              <span class="font-bold text-gray-500"> Plus de filtres </span> <x-icon.filters />
            </button>
          </div>
        </div>
    </div>
</div>
