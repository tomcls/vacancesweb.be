<div>
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Partner boxes</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <div class="flex rounded-md shadow-sm">
            <button type="button" wire:click="create" class=" rounded relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              New
              <x-icon.right class="text-gray-900 bg-gray-50" />
            </button>
          </div>
        </div>
    </div>
    <!-- partners Table -->
    <div class="flex-col space-y-4">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="px-1 max-w-0"  >
                    <x-input.checkbox wire:model="selectPage"  />
                </x-table.heading>
                <x-table.heading  sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                    ID
                </x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" >partner id</x-table.heading>
                <x-table.heading  >Post/holiday id</x-table.heading>
                <x-table.heading  >Box type</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="5">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $boxes->count() }}</strong> partners, do you want to select all <strong>{{ $boxes->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $boxes->total() }}</strong> box items.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($boxes as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id}})">{{ $row->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->partner_id.'#  '.$row->partner->code }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->box_id }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->box_type }}
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.cube class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No partner box found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
    </div>
    <!-- Save Box Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            {{$errors}}
            <x-slot name="title">Edit box item</x-slot>

            <x-slot name="content">
                <x-input.group for="partner" label="Partner" :error="$errors->first('editing.partner_id')">
                    <x-input.select  wire:model="editing.partner_id" id="partner">
                        <option value=""></option>
                        @foreach (\App\Models\Partner::get() as $p)
                            <option value="{{ $p->id }}">{{$p->code }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group for="type" label="type" :error="$errors->first('editing.box_type')">
                    <x-input.select  wire:model="editing.box_type" id="type">
                        <option value=""></option>
                        <option value="holiday">holiday</option>
                        <option value="article">article</option>
                    </x-input.select>
                </x-input.group>
                @if ($editing->box_type == 'article')
                    <x-input.group  for="searchPost" label="Wordpress post" :error="$errors->first('editing.box_id')">
                        <x-input.autocomplete 
                            wire:model.debounce.450ms="postSearch" 
                            wire:keyup.debounce.450ms="postsResult"
                            id="searchPost" 
                            name="searchPost" 
                            wireModel="postId" 
                            :rows="$posts" 
                            placeholder="Find a post" >
                            <x-slot:icon>
                            <x-icon.document class="flex-shrink-0 text-indigo-600"/>
                            </x-slot:icon>
                        </x-input.autocomplete>
                    </x-input.group>
                @elseif($editing->box_type == 'holiday')
                    <x-input.group  for="searchHoliday" label="Find Holidays" :error="$errors->first('editing.box_id')">
                        <x-input.autocomplete 
                            wire:model.debounce.450ms="holidaySearch" 
                            wire:keyup.debounce.450ms="holidaysResult"
                            id="searchHoliday" 
                            name="searchHoliday" 
                            wireModel="holidayId" 
                            :rows="$holidays" 
                            placeholder="Find holidays" >
                            <x-slot:icon>
                            <x-icon.palm class="flex-shrink-0 text-indigo-600"/>
                            </x-slot:icon>
                        </x-input.autocomplete>
                    </x-input.group>
                @endif
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    <!-- Delete partners Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete partner boxe(s)</x-slot>

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
