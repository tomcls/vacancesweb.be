<div class="mt-4">
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Holiday prices</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <div class="flex rounded-md shadow-sm">
            <x-input.select wire:model="perPage" id="perPage" class="block rounded-none font-semibold">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </x-input.select>
            <x-dropdown label="Actions" class="rounded-none  inline-flex">
                <x-dropdown.item type="button" wire:click="exportSelected" class="flex items-center space-x-2 rounded-none">
                    <x-icon.download class="text-cool-gray-400"/> <span>Export</span>
                </x-dropdown.item>
                <x-dropdown.item type="button" wire:click="$toggle('showDeleteModal')" class=" flex items-center space-x-2">
                    <x-icon.trash class="text-cool-blue-400"/> <span>Delete</span>
                </x-dropdown.item>
            </x-dropdown>
            <button type="button" wire:click="create" class=" relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              New
              <x-icon.right class="text-gray-900 bg-gray-50" />
            </button>
            <button type="button" wire:click="toggleShowFilters" class="hidden relative -ml-px sm:inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              @if ($showFilters)<x-icon.close /> @else  <x-icon.filters /> @endif
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
                            <x-input.group  for="filter-departure-from" label="Departure From">
                                <x-input.text wire:model.lazy="filters.departure-from" id="filter-departure-from" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/4">
                            <x-input.group  for="filter-info" label="info">
                                <x-input.text wire:model.lazy="filters.info" id="filter-info" />
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/4">
                            <x-input.group  for="filter-price" label="Price">
                                <x-input.text wire:model.lazy="filters.price" id="filter-price" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-price-customer" label="Price customer">
                                <x-input.text wire:model.lazy="filters.price-customer" id="filter-price-customer" />
                            </x-input.group>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 sm:pl-2 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-departure-date" label="Departure date">
                                <x-input.date wire:model="filters.departure-date" id="filter-departure-date" placeholder="MM/DD/YYYY" />
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

        <!-- Prices Table -->
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.heading class="px-1 max-w-0"  >
                        <x-input.checkbox wire:model="selectPage"  />
                    </x-table.heading>
                    <x-table.heading  sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                        ID
                    </x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('departure_date')" :direction="$sorts['departure_date'] ?? null">Departure date</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('departure_from')" :direction="$sorts['departure_from'] ?? null">Departure from</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('duration_days')" :direction="$sorts['duration_days'] ?? null">duration days</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('duration_nights')" :direction="$sorts['duration_nights'] ?? null">duration nights</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('price')" :direction="$sorts['price'] ?? null">Price</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('price_customer')" :direction="$sorts['price_customer'] ?? null">Price customer</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('discount')" :direction="$sorts['discount'] ?? null">Discount</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('lowest_price')" :direction="$sorts['lowest_price'] ?? null">Lowest Price</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('info')" :direction="$sorts['info'] ?? null">Info</x-table.heading>

                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="7">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $prices->count() }}</strong> prices, do you want to select all <strong>{{ $prices->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $prices->total() }}</strong> prices.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($prices as $row)
                    <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                        <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                        <x-table.cell >
                            <x-button.link wire:click="edit({{ $row->id }})">{{ $row->id }}</x-button.link>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button.link wire:click="edit({{ $row->id }})">{{  $row->date_for_humans }}</x-button.link>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button.link wire:click="edit({{ $row->id }})">{{  $row->departure_from }}</x-button.link>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->duration_days }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->duration_nights }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->price }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->price_customer }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->discount }}%
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->lowest_price }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->info }}
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.money class="h-8 w-8 text-sky-500" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No price found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $prices->links() }}
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

    <!-- Delete Prices Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Price</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>
     <!-- Save Price Modal -->
     <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit Price</x-slot>

            <x-slot name="content">
                <x-input.group for="departure_date_for_editing" label="Departure date" :error="$errors->first('editing.departure_date_for_editing')">
                    <x-input.date wire:model="editing.departure_date_for_editing" id="departure_date_for_editing" />
                </x-input.group>

                <x-input.group for="departure_from" label="Departure from" :error="$errors->first('editing.departure_from')">
                    <x-input.text wire:model.defer="editing.departure_from" id="departure_from" placeholder="Departure from" />
                </x-input.group>

                <x-input.group for="duration_days" label="Duration days" :error="$errors->first('editing.duration_days')">
                    <x-input.text wire:model.defer="editing.duration_days" id="duration_days" placeholder="Duration days" />
                </x-input.group>

                <x-input.group for="duration_nights" label="Duration nights" :error="$errors->first('editing.duration_nights')">
                    <x-input.text wire:model.defer="editing.duration_nights" id="duration_nights" placeholder="Duration nights" />
                </x-input.group>

                <x-input.group for="price" label="Price" :error="$errors->first('editing.price')">
                    <x-input.text wire:model.defer="editing.price" id="price" placeholder="Price" />
                </x-input.group>

                <x-input.group for="price_customer" label="Price customer" :error="$errors->first('editing.price_customer')">
                    <x-input.text wire:model.defer="editing.price_customer" id="price_customer" placeholder="Price customer"  />
                </x-input.group>

                <x-input.group for="discount" label="Discount" :error="$errors->first('editing.discount')">
                    <x-input.text wire:model.defer="editing.discount" id="discount" placeholder="Discount" />
                </x-input.group>

                <x-input.group for="info" label="Info" :error="$errors->first('editing.info')">
                    <x-input.text wire:model.defer="editing.info" id="info" placeholder="Info" />
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>