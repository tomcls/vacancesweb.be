<div>
    <!-- Control Bar Desktop -->
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Regions</h3>
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
              <input type="text" name="mobile-search-candidate" id="mobile-search-candidate" wire:model="filters.search" placeholder="Search regions..." class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:hidden" placeholder="Search">
              <input type="text" name="desktop-search-candidate" id="desktop-search-candidate" wire:model="filters.search" placeholder="Search regions..." class="hidden w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:block" placeholder="Search candidates">
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
                    </div>
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/4">
                            <x-input.group  for="filter-lang" label="lang">
                                <x-input.select wire:model="filters.lang" id="filter-lang">
                                    <option value="{{$filters['lang']}}" >{{$filters['lang']}}</option>
                                    @foreach (config('app.langs') as $lang)
                                    @if($lang != $filters['lang']) 
                                        <option value="{{ $lang }}">{{$lang }}</option>
                                    @endif
                                    @endforeach
                                </x-input.select>
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row ">
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

                    </div>
                    <div>
                        <x-button.primary wire:click="resetFilters" class="m-2 mx-0">Reset Filters</x-button.link>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- Regions Table -->
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
                    <x-table.heading sortable multi-column wire:click="sortBy('slug')" :direction="$sorts['slug'] ?? null" >slug</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('level')" :direction="$sorts['level'] ?? null" >Level</x-table.heading>
                    <x-table.heading  sortable multi-column wire:click="sortBy('custom')" :direction="$sorts['custom'] ?? null">Custom</x-table.heading>
                    <x-table.heading  >Centroid</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('active')" :direction="$sorts['active'] ?? null" >Active</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('total_holidays')" :direction="$sorts['total_holidays'] ?? null" >total holidays</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('total_houses')" :direction="$sorts['total_houses'] ?? null" >total houses</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="8">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $regions->count() }}</strong> regions, do you want to select all <strong>{{ $regions->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $regions->total() }}</strong> regions.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($regions as $row)
                    <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                        <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                        <x-table.cell >
                            <a href="{{ route('admin.region').'/'.$row->id }}">{{ $row->id }}</a>
                        </x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('admin.region').'/'.$row->id }}" class="inline-flex space-x-2 truncate text-sm leading-5">
                                <p class="text-cool-gray-600 truncate">
                                    {{ $row->name }}
                                </p>
                            </a>
                        </x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('admin.region').'/'.$row->id }}" class="inline-flex space-x-2 truncate text-sm leading-5">
                                <p class="text-cool-gray-600 truncate">
                                    {{ $row->slug }}
                                </p>
                            </a>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->level }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->custom }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->longitude }}, {{ $row->latitude }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->active }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->total_holidays }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->total_houses }}
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="8">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.map class="h-8 w-8 text-cool-gray-400" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No region found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $regions->links() }}
            </div>
        </div>
    </div>
    <!-- Delete Holidays Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Region(s)</x-slot>

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
