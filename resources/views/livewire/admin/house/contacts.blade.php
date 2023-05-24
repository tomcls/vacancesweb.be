<div>
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">House contacts</h3>
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
                            <x-input.group  for="filter-email" label="Email">
                                <x-input.text wire:model.lazy="filters.email" id="filter-email" />
                            </x-input.group>
                        </div>
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
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-firstname" label="Firstname">
                                <x-input.text wire:model.lazy="filters.firstname" id="filter-firstname" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-lastname" label="Lastname">
                                <x-input.text wire:model.lazy="filters.lastname" id="filter-lastname" />
                            </x-input.group>
                        </div>
                    </div>
                    <div class="flex flex-row space-x-2">
                        <div class="basis-1/2">
                            <x-input.group  for="filter-house_id" label="house_id">
                                <x-input.text wire:model.lazy="filters.house_id" id="filter-house_id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-user_id" label="user_id">
                                <x-input.text wire:model.lazy="filters.user_id" id="filter-user_id" />
                            </x-input.group>
                        </div>
                    </div>
                </div>
                <div class="w-1/2 pl-2 space-y-4">

                    <div class="flex flex-row space-x-2">
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
                    <x-button.link wire:click="resetFilters" class="absolute right-0 bottom-0 p-4 ">Reset Filters</x-button.link>
                </div>
            </div>
            @endif
        </div>

        <!-- Contacts Table -->
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.heading class="px-1 max-w-0"  >
                        <x-input.checkbox wire:model="selectPage"  />
                    </x-table.heading>
                    <x-table.heading  sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                        ID
                    </x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('house_id')" :direction="$sorts['house_id'] ?? null">house id</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('user_id')" :direction="$sorts['user_id'] ?? null">user id</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">Email</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('firstname')" :direction="$sorts['firstname'] ?? null">firstname</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('lastname')" :direction="$sorts['lastname'] ?? null">lastname</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('phone')" :direction="$sorts['phone'] ?? null">phone</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('lang')" :direction="$sorts['lang'] ?? null">lang</x-table.heading>
                    <x-table.heading >message</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">Date Created</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('updated_at')" :direction="$sorts['updated_at'] ?? null">Date Updated</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="12">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $contacts->count() }}</strong> contacts, do you want to select all <strong>{{ $contacts->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $contacts->total() }}</strong> contacts.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($contacts as $contact)
                    <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $contact->id }}">
                        <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $contact->id }}" /> </x-table.cell>
                        <x-table.cell >
                            {{ $contact->id }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $contact->house_id }}
                        </x-table.cell>
                        <x-table.cell>
                            <span class="text-cool-gray-900 font-medium">{{ $contact->user_id.'# '.$contact->user->firstname.' '.$contact->user->lastname }} </span> 
                            <br/><small> {{ $contact->user->email }}</small>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $contact->firstname }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $contact->lastname }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $contact->email }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $contact->phone }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $contact->lang }}
                        </x-table.cell>
                        <x-table.cell class="text-xs">
                            {{ $contact->message }}
                        </x-table.cell>

                        <x-table.cell>
                            {{ $contact->created_at }}
                        </x-table.cell>
                        
                        <x-table.cell>
                            {{ $contact->updated_at }}
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="12">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.user class="h-8 w-8 text-cool-gray-400" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No contacts found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
            <div>
                {{ $contacts->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Contacts Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Contact</x-slot>

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