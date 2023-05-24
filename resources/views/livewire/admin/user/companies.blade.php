<div>
    <!-- Control Bar Desktop -->
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Companies</h3>
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
              <input type="text" name="mobile-search-candidate" id="mobile-search-candidate" wire:model="filters.search" placeholder="Search Companies..." class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:hidden" placeholder="Search">
              <input type="text" name="desktop-search-candidate" id="desktop-search-candidate" wire:model="filters.search" placeholder="Search Companies..." class="hidden w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-sm leading-6 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:block" placeholder="Search candidates">
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
            <button type="button" wire:click="create" class="hidden relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
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
        <div class="bg-slate-100 rounded-md">
            @if ($showFilters)
            <div class="bg-cool-gray-200 p-4 rounded shadow-inner flex relative">
                <div class="w-1/2 pr-2 space-y-4">
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/4">
                            <x-input.group  for="filter-id" label="Id">
                                <x-input.text wire:model.lazy="filters.id" id="filter-id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-name" label="Name">
                                <x-input.text wire:model.lazy="filters.name" id="filter-name" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-vat" label="VAT">
                                <x-input.text wire:model.lazy="filters.vat" id="filter-vat" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-email" label="Email">
                                <x-input.text wire:model.lazy="filters.email" id="filter-email" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/4">
                            <x-input.group  for="filter-phone" label="Phone">
                                <x-input.text wire:model.lazy="filters.phone" id="filter-phone" />
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-street" label="Street">
                                <x-input.text wire:model.lazy="filters.street" id="filter-street" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-zip" label="Lastname">
                                <x-input.text wire:model.lazy="filters.zip" id="filter-zip" />
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-country" label="Country">
                                <x-input.text wire:model.lazy="filters.country" id="filter-country" />
                            </x-input.group>
                        </div>
                    </div>
                </div>
                <div class="w-1/2 pl-2 space-y-4">
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-date-min" label="Minimum created date">
                                <x-input.date wire:model="filters.date-created-min" id="filter-date-min" placeholder="MM/DD/YYYY" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-date-max" label="Maximum created date">
                                <x-input.date wire:model="filters.date-created-max" id="filter-date-max" placeholder="MM/DD/YYYY" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-active" label="Is active?">
                                <x-input.checkbox wire:model="filters.active" id="filter-active"  />
                            </x-input.group>
                        </div>
                    </div>
                    <x-button.link wire:click="resetFilters" class="absolute right-0 bottom-0 p-4 ">Reset Filters</x-button.link>
                </div>
            </div>
            @endif
        </div>

        <!-- Users Table -->
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
                    <x-table.heading sortable multi-column wire:click="sortBy('user_id')" :direction="$sorts['user_id'] ?? null" >user id</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('vat')" :direction="$sorts['vat'] ?? null">vat</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">Email</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('phone')" :direction="$sorts['Phone'] ?? null">Phone</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('street')" :direction="$sorts['street'] ?? null">street</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('zip')" :direction="$sorts['zip'] ?? null">zip</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">created at</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('active')" :direction="$sorts['active'] ?? null">Active</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="12">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $companies->count() }}</strong> companies, do you want to select all <strong>{{ $companies->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $companies->total() }}</strong> companies.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($companies as $company)
                    <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $company->id }}">
                        <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $company->id }}" /> </x-table.cell>
                        <x-table.cell >
                            <x-button.link wire:click="edit({{ $company->id }})">{{ $company->id }}</x-button.link>
                        </x-table.cell>
                        <x-table.cell>
                            <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                <x-button.link wire:click="edit({{ $company->id }})">{{ $company->name }}</x-button.link>
                            </span>
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">{{ $company->user_id.'# '.$company->user->firstname.' '.$company->user->lastname }} </span> 
                            <br/><small> {{ $company->user->email }}</small>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button.link wire:click="edit({{ $company->id }})">{{ $company->vat }}</x-button.link>
                        </x-table.cell>

                        <x-table.cell>
                            {{ $company->email }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $company->phone }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $company->street }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $company->zip }}
                        </x-table.cell>
                        
                        <x-table.cell>
                            {{ $company->created_at }}
                        </x-table.cell>

                        <x-table.cell>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-{{ $company->active }}-100 text-{{ $company->active }}-800 capitalize">
                                {{ $company->active }}
                            </span>
                        </x-table.cell>

                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="12">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.user class="h-8 w-8 text-cool-gray-400" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No companies found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $companies->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Users Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete User</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>

    <!-- Save User Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit User</x-slot>

            <x-slot name="content">
                <x-input.group for="name" label="Name" :error="$errors->first('editing.name')">
                    <x-input.text wire:model.lazy="editing.name" id="name" placeholder="Name" />
                </x-input.group>

                <x-input.group  for="user-search" label="User">
                    <x-input.autocomplete 
                        wire:model="userSearch" 
                        wire:keyup="usersResult"
                        id="user-search" 
                        name="user-search" 
                        wireModel="editing.user_id"
                        :rows="$users" 
                        placeholder="Find a user" >
                        <x-slot:icon>
                            <svg class="h-6 w-6 flex-shrink-0 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                            </svg>
                        </x-slot:icon>
                    </x-input.autocomplete>
                </x-input.group>

                <x-input.group for="vat" label="VAT" :error="$errors->first('editing.vat')">
                    <x-input.text wire:model.lazy="editing.vat" id="vat" placeholder="VAT" />
                </x-input.group>

                <x-input.group for="email" label="Email" :error="$errors->first('editing.email')">
                    <x-input.text wire:model.lazy="editing.email" id="email" placeholder="Email" />
                </x-input.group>
                <x-input.group for="phone" label="Phone" :error="$errors->first('editing.phone')">
                    <x-input.text wire:model.lazy="editing.phone" id="phone" placeholder="Phone" />
                </x-input.group>

                <x-input.group for="street" label="Street" :error="$errors->first('editing.street')">
                    <x-input.text wire:model.lazy="editing.street" id="street" placeholder="Street" />
                </x-input.group>

                <x-input.group for="street_number" label="Street number" :error="$errors->first('editing.street_number')">
                    <x-input.text wire:model.lazy="editing.street_number" id="street_number" placeholder="Street number"  />
                </x-input.group>
                <x-input.group for="street_box" label="Box" :error="$errors->first('editing.street_box')">
                    <x-input.text wire:model.lazy="editing.street_box" id="street_box" placeholder="Box"  />
                </x-input.group>

                <x-input.group for="zip" label="Zip" :error="$errors->first('editing.zip')">
                    <x-input.text wire:model.lazy="editing.zip" id="zip" placeholder="Zip" />
                </x-input.group>
                <x-input.group for="country" label="Country" :error="$errors->first('editing.country')">
                    <x-input.text wire:model.lazy="editing.country" id="country" placeholder="Country" />
                </x-input.group>

                <x-input.group for="active" label="Active" :error="$errors->first('editing.active')">
                    <x-input.checkbox wire:model.lazy="editing.active" id="active"  />
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>

@push('css')
  @vite(['node_modules/pikaday/css/pikaday.css'])
@endpush
@push('scripts')
  @vite(['resources/js/pickaday.js','resources/js/moment.js'])
@endpush