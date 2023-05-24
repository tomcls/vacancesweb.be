<div x-data="{ isOpen: false }">
    <div class="relative mt-2">
      <button {{$attributes}} @click.inside="isOpen = true" type="button" class="relative w-52 cursor-default rounded-md bg-white py-2.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm sm:leading-6" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
        <span class="block truncate">Location de vacances</span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
          </svg>
        </span>
      </button>
      <ul x-show="isOpen" @click.outside="isOpen = false" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
        <li class="text-gray-900 relative cursor-default select-none py-2 pl-8 pr-4" id="listbox-option-0" role="option">
          <span class="font-normal block truncate">Location de vacances</span>
          <span class="text-blue-500 absolute inset-y-0 left-0 flex items-center pl-1.5">
            <x-icon.house />
          </span>
        </li>
        <li class="text-gray-900 relative cursor-default select-none py-2 pl-8 pr-4" id="listbox-option-1" role="option">
            <span class="font-normal block truncate">Séjour</span>
            <span class="text-blue-500 absolute inset-y-0 left-0 flex items-center pl-1.5">
              <x-icon.hotel />
            </span>
        </li>
        <li class="text-gray-900 relative cursor-default select-none py-2 pl-8 pr-4" id="listbox-option-2" role="option">
            <span class="font-normal block truncate">Circuit</span>
            <span class="text-blue-500 absolute inset-y-0 left-0 flex items-center pl-1.5">
              <x-icon.map2 />
            </span>
        </li>
        <li class="text-gray-900 relative cursor-default select-none py-2 pl-8 pr-4" id="listbox-option-3" role="option">
            <span class="font-normal block truncate">Croisière</span>
            <span class="text-blue-500 absolute inset-y-0 left-0 flex items-center pl-1.5">
              <x-icon.boat />
            </span>
        </li>
        <li class="text-gray-900 relative cursor-default select-none py-2 pl-8 pr-4" id="listbox-option-4" role="option">
            <span class="font-normal block truncate">Club</span>
            <span class="text-blue-500 absolute inset-y-0 left-0 flex items-center pl-1.5">
              <x-icon.palm />
            </span>
        </li>
  
      </ul>
    </div>
  </div>