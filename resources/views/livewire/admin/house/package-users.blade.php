<div class="mt-4">
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">User packages</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <div class="flex rounded-md shadow-sm">
            <x-input.select wire:model="perPage" id="perPage" class="block rounded-none rounded-l font-semibold">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </x-input.select>
            <button type="button" wire:click="$toggle('showDeleteModal')" class=" relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Delete 
              <x-icon.trash class="text-gray-900 bg-gray-50" />
            </button>
            <button type="button" wire:click="openAssignModal" class=" relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Assign
              <x-icon.mail class="text-gray-900 bg-gray-50" />
            </button>
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
    <!-- packages Table -->
    <div class="flex-col space-y-4">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="px-1 max-w-0"  ><x-input.checkbox wire:model="selectPage"  /></x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">ID</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('user_id')" :direction="$sorts['user_id'] ?? null" >User_id</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('house_package_id')" :direction="$sorts['house_package_id'] ?? null" >Package id</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="4">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $packages->count() }}</strong> packages, do you want to select all <strong>{{ $packages->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $packages->total() }}</strong> packages.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($packages as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->package->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->house_package_id }})">{{ $row->user_id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->house_package_id }})">{{ $row->house_package_id }}</x-button.link>
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.cube class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No user package found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        <div>
            {{ $packages->links() }}
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
    <!-- Delete Packages Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Package</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>
    <!-- Save Package Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            {{$errors}}
            <x-slot name="title">Edit Package</x-slot>

            <x-slot name="content">
                <x-input.group for="user_id" label="User Id" :error="$errors->first('editing.user_id')">
                    <x-input.text wire:model.lazy="editing.user_id" id="user_id" placeholder="User id" />
                </x-input.group>
                <x-input.group for="house_package_id" label="Package id" :error="$errors->first('editing.house_package_id')">
                    <x-input.text wire:model.lazy="editing.house_package_id" id="house_package_id" placeholder="Package id" />
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>

    <!-- Assign Package Modal -->
    <form wire:submit.prevent="assign">
        <x-modal.dialog wire:model.defer="showAssignModal">
            <x-slot name="title">Assign package to user or to prospect</x-slot>

            <x-slot name="content">
                <x-input.group  for="email" label="User" :error="$errors->first('assign.email')">
                    <x-input.autocomplete 
                        wire:model="userSearch" 
                        wire:keyup="usersResult"
                        id="email" 
                        name="email" 
                        wireModel="assign.email"
                        :rows="$users" 
                        placeholder="Find a user" >
                        <x-slot:icon>
                            <x-icon.map class="flex-shrink-0 text-indigo-600"/>
                          </x-slot:icon>
                    </x-input.autocomplete>
                </x-input.group>
                <x-input.group  for="package_id" label="Package" :error="$errors->first('assign.house_package_id')">
                    <x-input.select wire:model.lazy="assign.house_package_id" id="package_id">
                        <option value="" ></option>
                        @foreach ($housePackages as $package)
                            <option value="{{ $package->id }}">{{$package->code }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>