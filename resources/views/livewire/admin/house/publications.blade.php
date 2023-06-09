<div class="mt-4">
    <!-- Control Bar Desktop -->
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">House publication</h3>
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
    <!-- Advanced Search -->
    <div class="bg-slate-100 rounded-md">
        @if ($showFilters)
        <div class="bg-cool-gray-200 p-4 rounded shadow-inner flex relative">
            <div class="w-1/2 pr-2 space-y-4">
                <div class="flex flex-row space-x-2">
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-house_id" label="house id">
                                <x-input.text wire:model.lazy="filters.house_id" id="filter-house_id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-publication-id" label="Publication id">
                                <x-input.text wire:model.lazy="filters.id" id="filter-id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-invoice_id" label="Invoice id">
                                <x-input.text wire:model.lazy="filters.invoice_id" id="filter-invoice_id" />
                            </x-input.group>
                        </div>
                    </div>
                </div>                
                <div class="flex flex-row space-x-2">
                    <div class="basis-1/2">
                        <x-input.group  for="filter-created-min" label="Minimum Created Date">
                            <x-input.date wire:model="filters.created-min" id="filter-created-min" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                    <div class="basis-1/2">
                        <x-input.group  for="filter-created-max" label="Maximum Created Date">
                            <x-input.date wire:model="filters.created-max" id="filter-created-max" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                </div>
                <x-button.link wire:click="resetFilters" class=" right-0 bottom-0 p-4 ">Reset Filters</x-button.link>
            </div>
            <div class="w-1/2 pl-2 space-y-4">

                <div class="flex flex-row space-x-2">
                    <div class="basis-1/2">
                        <x-input.group  for="filter-startdate-min" label="Minimum Start Date">
                            <x-input.date wire:model="filters.startdate-min" id="filter-startdate-min" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                    <div class="basis-1/2">
                        <x-input.group  for="filter-startdate-max" label="Maximum Start Date">
                            <x-input.date wire:model="filters.startdate-max" id="filter-startdate-max" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                </div>
                <div class="flex flex-row space-x-2">
                    <div class="basis-1/2">
                        <x-input.group  for="filter-enddate-min" label="Minimum End Date">
                            <x-input.date wire:model="filters.enddate-min" id="filter-enddate-min" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                    <div class="basis-1/2">
                        <x-input.group  for="filter-enddate-max" label="Maximum End Date">
                            <x-input.date wire:model="filters.enddate-max" id="filter-enddate-max" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- publications Table -->
    <div class="flex-col space-y-4">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="px-1 max-w-0"  ><x-input.checkbox wire:model="selectPage"  /></x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">ID</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('house_id')" :direction="$sorts['house_id'] ?? null">house id</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('user_id')" :direction="$sorts['user_id'] ?? null">user id</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('startdate')" :direction="$sorts['startdate'] ?? null" >Start date</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('enddate')" :direction="$sorts['enddate'] ?? null" >End date</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('invoice_id')" :direction="$sorts['invoice_id'] ?? null" >Invoice Id</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null" >Created</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="7">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $publications->count() }}</strong> publications, do you want to select all <strong>{{ $publications->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $publications->total() }}</strong> publications.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($publications as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->house_id }}<br/>
                        <small>{{ $row->house->houseTitle->name }}</small>
                        </x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->house->user->id.'# '.$row->house->user->firstname.' '.$row->house->user->lastname }}</x-button.link>
                        <br/><small> {{ $row->house->user->email }}</small>
                    </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->startdate_for_humans }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->enddate_for_humans }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->invoice_id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->created_for_humans }}</x-button.link>
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="8">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.calendar class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No publication found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        <div>
            {{ $publications->links() }}
        </div>
    </div>
    <!-- Delete Transactions Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Transaction</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>

    <!-- Save Transaction Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit or create a publication</x-slot>

            <x-slot name="content">
                <x-input.group  for="searchHouse" label="find house">
                    <x-input.autocomplete 
                        wire:model.debounce.450ms="houseSearch" 
                        wire:keyup.debounce.450ms="housesResult"
                        id="searchHouse" 
                        name="searchHouse" 
                        wireModel="editing.house_id" 
                        :rows="$houses" 
                        placeholder="Find a house" >
                        <x-slot:icon>
                        <x-icon.palm class="flex-shrink-0 text-indigo-600"/>
                        </x-slot:icon>
                    </x-input.autocomplete>
                </x-input.group>
                <x-input.group for="startdate" label="Start date" :error="$errors->first('editing.startdate_for_editing')">
                    <x-input.date wire:model="editing.startdate_for_editing" id="startdate" placeholder="Start date" />
                </x-input.group>
                <x-input.group for="enddate" label="End date" :error="$errors->first('editing.enddate_for_editing')">
                    <x-input.date wire:model="editing.enddate_for_editing" id="enddate" placeholder="Start date" />
                </x-input.group>
                @if (!$invoiceId)
                    <x-input.checkbox wire:model="createInvoice"  label="Create an invoice" />
                @endif
                
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
</div>
@push('css')
  @vite(['node_modules/pikaday/css/pikaday.css'])
@endpush
@push('scripts')
  @vite(['resources/js/pickaday.js','resources/js/moment.js'])
@endpush