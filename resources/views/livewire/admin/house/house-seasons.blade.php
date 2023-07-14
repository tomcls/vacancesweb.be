<div class="mt-4">
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">House season tarifications</h3>
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
    <!-- seasons Table -->
    <div class="flex-col space-y-4">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="px-1 max-w-0"  ><x-input.checkbox wire:model="selectPage"  /></x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">ID</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('startdate')" :direction="$sorts['startdate'] ?? null" >start date</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('enddate')" :direction="$sorts['enddate'] ?? null" >end date</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('day_price')" :direction="$sorts['day_price'] ?? null" >Day price</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('week_price')" :direction="$sorts['week_price'] ?? null" >Week price</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('weekend_price')" :direction="$sorts['weekend_price'] ?? null" >Weekend price</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('min_nights')" :direction="$sorts['min_nights'] ?? null" >Min nights</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="8">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $seasons->count() }}</strong> seasons, do you want to select all <strong>{{ $seasons->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $seasons->total() }}</strong> seasons.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($seasons as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->startdate_for_humans }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->enddate_for_humans }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->day_price }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->week_price }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->weekend_price }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->min_nights }}
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="8">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.calendar class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No season found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        <div>
            {{ $seasons->links() }}
        </div>
    </div>
    <!-- Delete Seasons Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Season</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>

    <!-- Save Season Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit Season</x-slot>

            <x-slot name="content">
                <x-input.group for="startdate_for_editing" label="Start date" :error="$errors->first('editing.startdate_for_editing')">
                    <x-input.date wire:model.lazy="editing.startdate_for_editing" id="startdate_for_editing" placeholder="Start date" />
                </x-input.group>

                <x-input.group for="enddate_for_editing" label="End date" :error="$errors->first('editing.enddate_for_editing')">
                    <x-input.date wire:model.lazy="editing.enddate_for_editing" id="enddate_for_editing" placeholder="End date" />
                </x-input.group>

                <x-input.group for="day_price" label="Day price" :error="$errors->first('editing.day_price')">
                    <x-input.text wire:model.lazy="editing.day_price" id="day_price" placeholder="Day price" />
                </x-input.group>

                <x-input.group for="week_price" label="Week price" :error="$errors->first('editing.week_price')">
                    <x-input.text wire:model.lazy="editing.week_price" id="week_price" placeholder="Week price" />
                </x-input.group>

                <x-input.group for="weekend_price" label="Weekend price" :error="$errors->first('editing.weekend_price')">
                    <x-input.text wire:model.lazy="editing.weekend_price" id="weekend_price" placeholder="Weekend price" />
                </x-input.group>

                <x-input.group for="min_nights" label="Min nights" :error="$errors->first('editing.min_nights')">
                    <x-input.text wire:model.lazy="editing.min_nights" id="min_nights" placeholder="Min nights"  />
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>