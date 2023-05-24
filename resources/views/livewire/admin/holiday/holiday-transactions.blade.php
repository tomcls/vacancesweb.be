<div class="mt-4">
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Holiday transactions</h3>
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
                            <x-input.group  for="filter-publication-id" label="Transaction id">
                                <x-input.text wire:model.lazy="filters.id" id="filter-id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-invoice_id" label="Invoice id">
                                <x-input.text wire:model.lazy="filters.invoice_id" id="filter-invoice_id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-user_id" label="User id">
                                <x-input.text wire:model.lazy="filters.user_id" id="filter-user_id" />
                            </x-input.group>
                        </div>
                    </div>
                </div>  
                <div class="flex flex-row space-x-2">
                    <div class="basis-1/2">
                        <x-input.group  for="filter-price" label="Price">
                            <x-input.text wire:model.lazy="filters.price" id="filter-price" />
                        </x-input.group>
                    </div>
                    <div class="basis-1/2">
                        <x-input.group for="fitler-payment_status" label="Invoice status" >
                            <x-input.select  wire:model="filters.payment_status"  id="fitler-payment_status" class=" py-2">
                                <option value=""></option>
                                @foreach (\App\Data\Enums\InvoicePaymentStatusEnum::cases() as $unit)
                                    <option value="{{ $unit }}">{{$unit }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>
                    </div>
                </div>    
                <x-button.link wire:click="resetFilters" class=" right-0 bottom-0 p-4 ">Reset Filters</x-button.link>
            </div>
            <div class="w-1/2 pl-2 space-y-4">
          
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
                <div class="flex flex-row space-x-2">
                    <div class="basis-1/2">
                        <x-input.group  for="filter-datepayed-min" label="Minimum Date Payed">
                            <x-input.date wire:model="filters.datepayed-min" id="filter-datepayed-min" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                    <div class="basis-1/2">
                        <x-input.group  for="filter-datepayed-max" label="Maximum Date Payed">
                            <x-input.date wire:model="filters.datepayed-max" id="filter-datepayed-max" placeholder="MM-DD-YYYY" />
                        </x-input.group>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- transactions Table -->
    <div class="flex-col space-y-4">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="px-1 max-w-0"  ><x-input.checkbox wire:model="selectPage"  /></x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">ID</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('invoice_id')" :direction="$sorts['invoice_id'] ?? null" >Invoice Id</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('reference')" :direction="$sorts['reference'] ?? null" >Reference</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('user_id')" :direction="$sorts['user_id'] ?? null" >User Id</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('price')" :direction="$sorts['price'] ?? null" >Price</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('type')" :direction="$sorts['type'] ?? null" >Type</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('date_payed')" :direction="$sorts['date_payed'] ?? null" >Date Payed</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('payment_status')" :direction="$sorts['payment_status'] ?? null" >Status</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null" >Created</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="8">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $transactions->count() }}</strong> transactions, do you want to select all <strong>{{ $transactions->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $transactions->total() }}</strong> transactions.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($transactions as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->invoice->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->reference }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->invoice->user_id }}# {{ $row->invoice->user->firstname }} {{ $row->invoice->user->lastname }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">€{{ $row->price }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->type }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->invoice->date_payed_for_humans }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->invoice->payment_status }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->invoice->date_created_for_humans }}
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="8">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.list class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No transaction found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        <div>
            {{ $transactions->links() }}
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
            <x-slot name="title">Edit or create a transaction</x-slot>

            <x-slot name="content">
                <x-input.group for="invoice_id" label="Invoice Id" :error="$errors->first('editing.invoice_id')">
                    <x-input.text wire:model="editing.invoice_id" id="invoice_id" placeholder="if invoiceId empty, a new invoice is created with this transaction" />
                </x-input.group>
                <x-input.group  for="user_id" label="User" :error="$errors->first('invoice.user_id')">
                    <x-input.autocomplete 
                        wire:model="userSearch" 
                        wire:keyup="usersResult"
                        id="user_id" 
                        name="user_id" 
                        wireModel="invoice.user_id"
                        :rows="$users" 
                        placeholder="Find a user" >
                        <x-slot:icon>
                            <x-icon.user class="flex-shrink-0 text-indigo-600"/>
                          </x-slot:icon>
                    </x-input.autocomplete>
                </x-input.group>

                <x-input.group for="price" label="Day price" :error="$errors->first('editing.price')">
                    <x-input.text wire:model="editing.price" id="price" placeholder="Price" />
                </x-input.group>
                <x-input.group for="datePayed" label="Date payed" :error="$errors->first('invoice.date_payed_for_editing')">
                    <x-input.date wire:model="invoice.date_payed_for_editing" id="datePayed" placeholder="Date payed" />
                </x-input.group>

                <x-input.group for="payment_status" label="Transaction status" :error="$errors->first('invoice.payment_status')">
                    <x-input.select  wire:model="invoice.payment_status"  id="payment_status">
                        @foreach (\App\Data\Enums\InvoicePaymentStatusEnum::cases() as $unit)
                           
                            <option value="{{ $unit }}">{{$unit }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>