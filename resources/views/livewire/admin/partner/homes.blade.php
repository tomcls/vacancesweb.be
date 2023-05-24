<div>
    <h1 class="text-2xl font-semibold text-gray-900">Partner heros</h1>
    <div class="flex-col sm:flex sm:flex-row space-x-0 sm:space-x-2">
        <div class=" sm:w-3/12 flex   space-x-2 ">
            
        </div>
        <div class="grow ">
          
        </div>
        <div class="flex   space-x-1 ">
            <div class="w-4/12 sm:w-6/12 justify-end justify-items-end items-center content-center mb-3 " >
                <x-button.primary wire:click="create"  >
                    <x-icon.plus/> New
                </x-button.primary>
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
                <x-table.heading  >Hero id</x-table.heading>
                <x-table.heading  >Hero type</x-table.heading>
                <x-table.heading  >image</x-table.heading>
                <x-table.heading >testimonial url</x-table.heading>
                <x-table.heading sortable multi-column wire:click="sortBy('lang')" :direction="$sorts['lang'] ?? null">conference url</x-table.heading>
            </x-slot>

            <x-slot name="body">
                @if ($selectPage)
                <x-table.row class="bg-cool-gray-200" wire:key="row-message">
                    <x-table.cell colspan="7">
                        @unless ($selectAll)
                        <div>
                            <span>You have selected <strong>{{ $heros->count() }}</strong> heros, do you want to select all <strong>{{ $heros->total() }}</strong>?</span>
                            <x-button.link wire:click="selectAll" class="ml-1 text-blue-600">Select All</x-button.link>
                        </div>
                        @else
                        <span>You are currently selecting all <strong>{{ $heros->total() }}</strong> heros.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif

                @forelse ($heros as $row)
                <x-table.row wire:loading.class.delay="opacity-70" wire:key="row-{{ $row->id }}">
                    <x-table.cell class="px-1"  ><x-input.checkbox wire:model="selected" value="{{ $row->id }}" /> </x-table.cell>
                    <x-table.cell >
                        <x-button.link wire:click="edit({{ $row->id}})">{{ $row->id }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        <x-button.link wire:click="edit({{ $row->id }})">{{ $row->partner_id.'#  '.$row->partner->code }}</x-button.link>
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->hero_id }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->hero_type }}
                    </x-table.cell>
                    <x-table.cell>
                        <img src="{{ $row->url('small') }}" alt="Profile Photo" class="h-10 w-14 rounded">
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->testimonial_url }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $row->conference_url }}
                    </x-table.cell>
                </x-table.row>
                @empty
                <x-table.row>
                    <x-table.cell colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <x-icon.heart class="h-8 w-8 text-cool-gray-400" />
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">No partner found...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
                @endforelse
            </x-slot>
        </x-table>
    </div>
    <!-- Save Hero Modal -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            {{$errors}}
            <x-slot name="title">Edit Hero</x-slot>

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
                <x-input.group for="type" label="type" :error="$errors->first('editing.hero_type')">
                    <x-input.select  wire:model="editing.hero_type" id="type">
                        <option value="holiday">holiday</option>
                        <option value="article">article</option>
                    </x-input.select>
                </x-input.group>
                @if ($editing->hero_type == 'article')
                    <x-input.group  for="searchPost" label="Wordpress post" :error="$errors->first('editing.hero_id')">
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
                @else
                    <x-input.group  for="searchHoliday" label="Find Holidays" :error="$errors->first('editing.hero_id')">
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
                <x-input.group for="testimonial_url" label="Testimonial url" :error="$errors->first('editing.testimonial_url')">
                    <x-input.text wire:model="editing.testimonial_url" id="testimonial_url"/>
                </x-input.group>
                <x-input.group for="conference_url" label="Conference url" :error="$errors->first('editing.conference_url')">
                    <x-input.text wire:model="editing.conference_url" id="conference_url"/>
                </x-input.group>
                <x-input.group inline label="Image" for="file" :error="$errors->first('upload')">
                    <x-input.file-upload wire:model="upload" id="file">
                        @if ($upload)
                            <img src="{{ $upload->temporaryUrl() }}" alt="Profile Photo" class="h-20 w-20 rounded-full">
                        @else
                            <img src="{{ $editing->url('small') }}" alt="Profile Photo" class="h-20 w-20 rounded-full">
                        @endif
                    </x-input.file-upload>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>

                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    <!-- Delete partners Modal -->
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model.defer="showDeleteModal">
            <x-slot name="title">Delete partner</x-slot>

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
