<div>
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Partner catalogs</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          <div class="flex rounded-md shadow-sm">
            <x-dropdown label="Actions" class="rounded-none rounded-l  inline-flex">
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
                <div class="w-full  space-y-4">
                    <div class="flex flex-col sm:flex-row sm:space-x-2">
                        <div class="basis-1/4">
                            <x-input.group for="filters-partner" label="Partner" >
                                <x-input.select  wire:model="filters.partner_id" id="filters-partner">
                                    <option value=""></option>
                                    @foreach (\App\Models\Partner::get() as $p)
                                        <option value="{{ $p->id }}">{{$p->code }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>
                        </div>
                        <div class="basis-1/4">
                            <x-input.group  for="filter-id" label="Id">
                                <x-input.text wire:model.lazy="filters.id" id="filter-id" />
                            </x-input.group>
                        </div>
                        <div class="basis-1/2">
                            <x-input.group  for="filter-holiday_id" label="holiday_id">
                                <x-input.text wire:model.lazy="filters.holiday_id" id="filter-holiday_id" />
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
                </div>
            </div>
            @endif
        </div>

        <!-- partners Table -->
        <div class="flex-col space-y-4" drag-root="reorder">
            <x-table >
                <x-slot name="head">
                    <x-table.heading class="px-1 max-w-0"  >
                        <x-input.checkbox wire:model="selectPage"  />
                    </x-table.heading>
                    <x-table.heading  sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                        ID
                    </x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('partner_id')" :direction="$sorts['partner_id'] ?? null" >partner id</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('holiday_id')" :direction="$sorts['holiday_id'] ?? null"  >holiday id</x-table.heading>
                    <x-table.heading sortable multi-column wire:click="sortBy('sort')" :direction="$sorts['sort'] ?? null" >sort</x-table.heading>
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                        <x-table.cell colspan="5">
                            @unless ($selectAll)
                            <div>
                                <span>You have selected <strong>{{ $catalogs->count() }}</strong> partners, do you want to select all <strong>{{ $catalogs->total() }}</strong>?</span>
                                <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                            </div>
                            @else
                            <span>You are currently selecting all <strong>{{ $catalogs->total() }}</strong> catalog items.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($catalogs as $row)
                    <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}" draggable="true" drag-item="{{ $row->id }}">
                        <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                        <x-table.cell >
                            <x-button.link wire:click="edit({{ $row->id}})">{{ $row->id }}</x-button.link>
                        </x-table.cell>
                        <x-table.cell>
                            <x-button.link wire:click="edit({{ $row->id }})">{{ $row->partner_id.'#  '.$row->partner->code }}</x-button.link>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->holiday_id }} <small>{{ $row->holiday->holidayTitle->name }}</small> 
                        </x-table.cell>
                        <x-table.cell>
                            {{ $row->sort }}
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="flex justify-center items-center space-x-2">
                                <x-icon.catalog class="h-8 w-8 text-cool-gray-400" />
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">No partner catalog found...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>
        </div>
    </div>
    <!-- Save Catalog Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            {{$errors}}
            <x-slot name="title">Edit catalog item</x-slot>

            <x-slot name="content">
                <x-input.group  for="lang" label="Lang" :error="$errors->first('editing.lang')">
                    <x-input.select wire:model="editing.lang" id="lang" >
                        <option value="" ></option>
                        @foreach (config('app.langs') as $g)
                            <option value="{{ $g }}">{{$g }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group for="partner" label="Partner" :error="$errors->first('editing.partner_id')">
                    <x-input.select  wire:model="editing.partner_id" id="partner">
                        <option value=""></option>
                        @foreach (\App\Models\Partner::get() as $p)
                            <option value="{{ $p->id }}">{{$p->code }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group  for="searchHoliday" label="Find Holidays" :error="$errors->first('editing.holiday_id')">
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
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
    <!-- Delete partners Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete partner catalog(s)</x-slot>

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

<script>
    window.onload = (event) => {
        console.log('loaded')
        window.Livewire.on('initDragAndDrop', () => initDragAndDrop());
        function initDragAndDrop() {
            let root = document.querySelector('[drag-root]');
            root.querySelectorAll('[drag-item]').forEach(el => {
            el.addEventListener('dragstart', e => {
                console.log('dragstart',e.target);
                e.target.setAttribute('dragging', true);
            });
            el.addEventListener('drop', e => {
                let draggingEl = root.querySelector('[dragging]')
                
                if(e.target.nodeName === "TD" ) {
                    console.log('drop',e.target.nodeName)
                    e.target.parentElement.parentElement.before(draggingEl)
                } else if(e.target.nodeName === "TR" ) {
                    console.log('drop TR',e.target.nodeName)
                    e.target.parentElement.parentElement.before(draggingEl)
                } else if(e.target.nodeName === "BUTTON" ) {
                    console.log('drop BUTTON',e.target.nodeName)
                    e.target.parentElement.parentElement.parentElement.before(draggingEl)
                }  else {
                    console.log('drop else',e.target.nodeName,e.target)
                    e.target.parentElement.parentElement.parentElement.before(draggingEl)
                }
                let component = window.Livewire.find(
                    e.target.closest('[wire\\:id]').getAttribute('wire:id')
                );
                let orderIds = Array.from(root.querySelectorAll('[drag-item]'))
                    .map(itemEl => itemEl.getAttribute('drag-item'));
                console.log(orderIds);
               window.Livewire.emit('reorder',orderIds);
            });

            el.addEventListener('dragenter', e => {
                if(e.target.nodeName === "DIV" && e.target.classList.contains('text-container')) {
                    e.target.parentElement.getElementsByTagName('IMG')[0].classList.add('opacity-50')
                } else {
                    let el = e.target.getElementsByTagName('img')[0];
                    if(el && !el.classList.contains('opacity-50')) {
                    el.classList.add('opacity-50');
                    }
                }
                e.preventDefault()
            });

            el.addEventListener('dragover', e => e.preventDefault());

            el.addEventListener('dragleave', e => {});

            el.addEventListener('dragend', e => { e.target.removeAttribute('dragging')});
            });
        }
        initDragAndDrop();
    };
  </script>