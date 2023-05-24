<div>
    <!-- Control Bar Desktop -->
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Holidays</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <label for="mobile-search-candidate" class="sr-only">Search</label>
          <label for="desktop-search-candidate" class="sr-only">Search</label>
          <div class="flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
              </div>
              <input type="text" name="mobile-search-candidate" id="mobile-search-candidate" wire:model="filters.search" placeholder="Search holidays..." class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:hidden" placeholder="Search">
              <input type="text" name="desktop-search-candidate" id="desktop-search-candidate" wire:model="filters.search" placeholder="Search holidays..." class="hidden w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:block" placeholder="Search candidates">
            </div>
            <x-input.select wire:model="perPage" id="perPage" class="hidden sm:block rounded-none font-semibold">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </x-input.select>
            <x-dropdown label="Actions" class="rounded-none hidden sm:inline-flex">
                <x-dropdown.item type="button" wire:click="exportSelected" class="flex items-center space-x-2 rounded-none">
                    <x-icon.download class="text-cool-gray-400"/> <span>Export</span>
                </x-dropdown.item>
                <x-dropdown.item type="button" wire:click="$toggle('showDeleteModal')" class=" flex items-center space-x-2">
                    <x-icon.trash class="text-cool-blue-400"/> <span>Delete</span>
                </x-dropdown.item>
            </x-dropdown>
            <button type="button" wire:click="new" class="hidden relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              New
              <x-icon.right class="text-gray-900 bg-gray-50" />
            </button>
            <button type="button" wire:click="toggleShowFilters" class="hidden relative -ml-px sm:inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              @if ($showFilters)<x-icon.close /> @else  <x-icon.filters /> @endif
            </button>
          </div>
        </div>
    </div>
    <!-- Control Bar Mobile -->
    <div class="sm:hidden">
        <div class="mt-3 sm:ml-4 sm:mt-0">
            <label for="mobile-search-candidate" class="sr-only">Search</label>
            <div class="flex rounded-md shadow-sm">
              <x-input.select wire:model="perPage" id="perPage" class="rounded-none font-semibold">
                  <option value="10">10 per page</option>
                  <option value="25">25 per page</option>
                  <option value="50">50 per page</option>
              </x-input.select>
              <x-dropdown label="Actions" class="rounded-none ">
                  <x-dropdown.item type="button" wire:click="exportSelected" class="flex items-center space-x-2 rounded-none">
                      <x-icon.download class="text-cool-gray-400"/> <span>Export</span>
                  </x-dropdown.item>
                  <x-dropdown.item type="button" wire:click="$toggle('showDeleteModal')" class="flex items-center space-x-2">
                      <x-icon.trash class="text-cool-blue-400"/> <span>Delete</span>
                  </x-dropdown.item>
                  <x-dropdown.item type="button" wire:click="create" class="flex items-center space-x-2">
                      <x-icon.trash class="text-cool-blue-400"/> <span>New</span>
                  </x-dropdown.item>
              </x-dropdown>
              <button type="button" wire:click="create" class="relative -ml-px inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                New
                <x-icon.right class="text-gray-900 bg-gray-50" />
              </button>
              <button type="button" wire:click="toggleShowFilters" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                
                @if ($showFilters)<x-icon.close class="text-white" /> @else  <x-icon.filters /> @endif
              </button>
            </div>
          </div>
    </div>
    <div class="py-4 space-y-4">
        
        <!-- Advanced Search -->
        <div class="bg-slate-100 rounded-md" >
            @if ($showFilters)
            <div class="flex-col sm:flex-row bg-cool-gray-200 p-4 rounded shadow-inner flex relative">
                <div class="w-full sm:w-1/2 pr-2 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:space-x-2">
                        <div class="basis-1/4">
                            <x-input.group  for="filter-id" label="Id">
                                <x-input.text wire:model.lazy="filters.id" id="filter-id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-name" label="name">
                                <x-input.text wire:model.lazy="filters.name" id="filter-name" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/4">
                            <x-input.group  for="filter-type-id" label="Id">
                                <x-input.select wire:model="filters.type-id" id="filter-type-id">
                                    <option value="" >Select type...</option>
                                    @foreach ($holidayTypes as $value => $type)
                                    <option value="{{ $type->id }}">{{'#'.$type->id.' '.$type->code }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row sm:space-x-2">
                        <div class="basis-1/4">
                            <x-input.group  for="filter-lang" label="lang">
                                <x-input.select wire:model="filters.lang" id="filter-lang">
                                    <option value="" ></option>
                                    @foreach (config('app.langs') as $lang)
                                        <option value="{{ $lang }}">{{$lang }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-user-search" label="User">
                                <x-input.autocomplete 
                                    wire:model="userSearch" 
                                    wire:keyup="usersResult"
                                    id="filter-user-search" 
                                    name="filter-user-search" 
                                    wireModel="filters.user-id"
                                    :rows="$users" 
                                    placeholder="Find a user" >
                                    <x-slot:icon>
                                        <svg class="h-6 w-6 flex-shrink-0 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                        </svg>
                                    </x-slot:icon>
                                </x-input.autocomplete>
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row ">
                        <div class="basis-1/4">
                                <x-input.checkbox wire:model="filters.has-position" id="filter-has-position"  label="Has position"/>
                        </div>
                        <div class="basis-1/4">
                                <x-input.checkbox wire:model="filters.no-position" id="filter-no-position"  label="No position" />
                        </div>
                        <div class="basis-1/4">
                                <x-input.checkbox wire:model="filters.is-active" id="filter-is-active" label="Is active" />
                        </div>
                        <div class="basis-1/4">
                                <x-input.checkbox wire:model="filters.not-active" id="filter-not-active" label="Not active"  />
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 sm:pl-2 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-date-min" label="Minimum Date">
                                <x-input.date wire:model="filters.date-created-min" id="filter-date-min" placeholder="MM/DD/YYYY" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-date-max" label="Maximum Date">
                                <x-input.date wire:model="filters.date-created-max" id="filter-date-max" placeholder="MM/DD/YYYY" />
                            </x-input.group>
                        </div>
                    </div>
                    <div>
                        <x-button.primary wire:click="resetFilters" class="m-2 mx-0">Reset Filters</x-button.link>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Holidays Table -->
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.heading class="px-1 max-w-0"  >
                        <x-input.checkbox wire:model="selectPage"  />
                    </x-table.heading>
                    <x-table.heading  sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                        ID
                    </x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" >name</x-table.heading>
                    <x-table.heading  >type</x-table.heading>
                    <x-table.heading  >User id</x-table.heading>
                    <x-table.heading  >Position</x-table.heading>
                    <x-table.heading >Active</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('lang')" :direction="$sorts['lang'] ?? null">lang</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="7">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $holidays->count() }}</strong> holidays, do you want to select all <strong>{{ $holidays->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $holidays->total() }}</strong> holidays.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($holidays as $row)
                    <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->holiday->id }}">
                        <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->holiday->id }}" /> </x-table.cell>
                        <x-table.cell >
                            <a href="{{ route('admin.holiday').'/'.$row->holiday->id }}">{{ $row->holiday->id }}</a>
                        </x-table.cell>
                        <x-table.cell>
                            <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                <p class="text-cool-gray-600 truncate">
                                    <a href="{{ route('admin.holiday').'/'.$row->holiday->id }}">{{ $row->name }}</a>
                                    
                                </p>
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->holiday->holidayType->code }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->holiday->user_id }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->holiday->hasPosition }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->holiday->active }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->lang }}
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.palm class="h-8 w-8 text-cool-gray-400" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No holiday found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $holidays->links() }}
            </div>
            <div class="w-40 content-center items-center">
                <x-input.group inline borderless paddingless for="perPage" label="Per page" >
                    <x-input.select wire:model="perPage" id="perPage" >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </x-input.select>
                </x-input.group>
            </div>
        </div>
    </div>
    <!-- Delete Holidays Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Holiday</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>
</div>
@push('css')
  @vite(['node_modules/pikaday/css/pikaday.css'])
@endpush
@push('scripts')
@vite(['resources/js/pickaday.js','resources/js/moment.js'])
@endpush