<div class="mt-4">
    <!-- Control Bar Desktop -->
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Documents</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          
          <div class="flex  shadow-sm">
            <x-input.select wire:model="filters.lang" id="filter-lang" class="rounded-l rounded-none">
                <option value="" ></option>
                @foreach (config('app.langs') as $lg)
                    <option value="{{ $lg }}">{{$lg }}</option>
                @endforeach
            </x-input.select>
            <button type="button" wire:click="$toggle('showDeleteModal')" class="rounded-none relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Delete
              <x-icon.trash class="text-gray-900 bg-gray-50" />
            </button>
            <button type="button" wire:click="save" class="rounded-r relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Save
              <x-icon.right class="text-gray-900 bg-gray-50" />
            </button>
          </div>
        </div>
    </div>
    <!-- documents Table -->
    <div class="flex-col space-y-4">
        <x-table>
            <x-slot name="head">
                <x-table.heading class="px-1 max-w-0"  ><x-input.checkbox wire:model="selectPage"  /></x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">ID</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" >name</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('origin')" :direction="$sorts['origin'] ?? null" >Original file name</x-table.heading>
                <x-table.heading  >public file name</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="5">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $documents->count() }}</strong> documents, do you want to select all <strong>{{ $documents->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $documents->total() }}</strong> documents.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($documents as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->document->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->document->id }}" /> </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->document->id }})">{{ $row->document->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->document->id }})">{{ $row->name }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link ><a href="{{ $row->document->url() }}" class="" target="blank">{{ $row->document->origin }}</a></x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <a href="{{ $row->document->url() }}" class="" target="blank">{{ $row->document->name }}</a>
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="5">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.document class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No document found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
        <div>
            {{ $documents->links() }}
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
    <!-- Delete Documents Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete Document</x-slot>

            <x-slot name="content">
                <div class="py-8 text-cool-gray-700">Are you sure you? This action is irreversible.</div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Delete</x-button.primary>
            </x-slot>
        </x-modal.confirmation>
    </form>
    <!-- Save Document Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            {{$errors}}
            <x-slot name="title">Edit Document</x-slot>

            <x-slot name="content">
                <x-input.group  for="langForEditing" label="Lang" :error="$errors->first('editing.lang')">
                    <x-input.select wire:model="langForEditing" id="langForEditing" >
                        <option value="" ></option>
                        @foreach (config('app.langs') as $g)
                            <option value="{{ $g }}">{{$g }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <div>
                    @foreach ($titles as $lg => $title)
                        <x-input.group  label="{{$lg}}" for="name_{{$lg}}" hidden="{{$langForEditing!=$lg?1:0}}" :error="$errors->first('title.name')">
                            <x-input.text 
                                wire:model.lazy="titles.{{$lg}}.name"  
                                id="name_{{$lg}}"  
                                x-ref="name_{{$lg}}">
                                <x-slot:leadingIcon>
                                    <x-icon.edit class="flex-shrink-0 text-indigo-600"/>
                                </x-slot:leadingIcon>
                            </x-input.text>
                        </x-input.group>
                    @endforeach
                </div>
                <x-input.group inline label="Document" for="file" :error="$errors->first('upload')">
                    <x-input.file-upload wire:model="upload" id="file">
                    </x-input.file-upload>
                    <p class="text-xs pt-5">
                        @if ($upload)
                        {{ $upload->getClientOriginalName() }}
                        @else
                            <a href="{{ $editing->url() }}" class="" target="blank">{{ $editing->name }}</a>
                        @endif
                    </p> 
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>