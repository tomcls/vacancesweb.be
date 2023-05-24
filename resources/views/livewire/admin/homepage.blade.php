<div>
     <!-- Control Bar Desktop -->
    <div class="sm:border-b border-gray-200 sm:pb-5 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Homepage</h3>
        <div class="mt-3 sm:ml-4 sm:mt-0">
          
          <div class="flex  shadow-sm">
            <x-input.select wire:model="lang" id="filter-lang" class="rounded-l rounded-none">
                <option value="" ></option>
                @foreach (config('app.langs') as $lg)
                    <option value="{{ $lg }}">{{$lg }}</option>
                @endforeach
            </x-input.select>
            <button type="button" wire:click="save" class="rounded-r relative -ml-px sm:inline-flex items-center gap-x-1.5  px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Save
              <x-icon.right class="text-gray-900 bg-gray-50" />
            </button>
          </div>
        </div>
    </div>
    <div class="container mx-auto px-5 py-2 lg:px-32 pt-8">
            <h2 class="text-xl font-semibold text-gray-900 pb-5">Hero</h2>
            <div class="-m-1 flex flex-wrap md:-m-2">
                <div class="flex sm:w-1/2 flex-wrap">
                    <div class=" h-96 w-full  md:p-1  ">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('hero1')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['hero1']['image']}}" />
                            </a>
            
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-8 self-end rounded-b-lg">
                                <h2 class="text-2xl font-medium text-white ">{{$homepage[$lang]['hero1']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex sm:w-1/2 flex-wrap">
                    
                    <div class="block w-full  sm:w-1/2 sm:h-1/2 p-1 md:p-1">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('hero2')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['hero2']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['hero2']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="w-full sm:w-1/2 sm:h-1/2 p-1 md:p-1">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('hero3')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['hero3']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['hero3']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/2 h-1/2 p-1 md:p-1">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('hero4')">
                            <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['hero4']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['hero4']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/2 h-1/2 p-1 md:p-1">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('hero5')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['hero5']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['hero5']['title']}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <h2 class="text-xl font-semibold text-gray-900 pb-5">Holidays</h2>
                <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-3 xl:gap-x-8">
                    <li class="relative ">
                        <div class="group relative">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                              <img src="{{$homepage[$lang]['holiday1']['image']}}"  class="object-cover object-center">
                              
                              <div class="absolute inset-x-0    ">
                                <div aria-hidden="true" class="absolute  top-5 right-5 rounded-full bg-blue-900 h-24 w-24 text-center pt-4">
                                    <p class="relative text-lg font-semibold text-white ">apd <br/>€{{$homepage[$lang]['holiday1']['price']}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between space-x-8 text-base font-medium text-gray-900">
                              <h3>
                                <a wire:click="openHolidayModal('holiday1')">
                                  <span aria-hidden="true" class="absolute inset-0"></span>
                                  {{$homepage[$lang]['holiday1']['title']}}
                                </a>
                              </h3>
                              <p>{{$homepage[$lang]['holiday1']['type']}}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{$homepage[$lang]['holiday1']['info']}}</p>
                        </div>
                    </li>
                    <li class="relative ">
                        <div class="group relative">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                              <img src="{{$homepage[$lang]['holiday2']['image']}}"  class="object-cover object-center">
                              
                              <div class="absolute inset-x-0    ">
                                <div aria-hidden="true" class="absolute  top-5 right-5 rounded-full bg-blue-900 h-24 w-24 text-center pt-4">
                                    <p class="relative text-lg font-semibold text-white ">apd <br/>€{{$homepage[$lang]['holiday2']['price']}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between space-x-8 text-base font-medium text-gray-900">
                              <h3>
                                <a wire:click="openHolidayModal('holiday2')">
                                  <span aria-hidden="true" class="absolute inset-0"></span>
                                  {{$homepage[$lang]['holiday2']['title']}}
                                </a>
                              </h3>
                              <p>{{$homepage[$lang]['holiday2']['type']}}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{$homepage[$lang]['holiday2']['info']}}</p>
                        </div>
                    </li>
                    <li class="relative ">
                        <div class="group relative">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                              <img src="{{$homepage[$lang]['holiday3']['image']}}"  class="object-cover object-center">
                              
                              <div class="absolute inset-x-0    ">
                                <div aria-hidden="true" class="absolute  top-5 right-5 rounded-full bg-blue-900 h-24 w-24 text-center pt-4">
                                    <p class="relative text-lg font-semibold text-white ">apd <br/>€{{$homepage[$lang]['holiday3']['price']}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between space-x-8 text-base font-medium text-gray-900">
                              <h3>
                                <a wire:click="openHolidayModal('holiday3')">
                                  <span aria-hidden="true" class="absolute inset-0"></span>
                                  {{$homepage[$lang]['holiday3']['title']}}
                                </a>
                              </h3>
                              <p>{{$homepage[$lang]['holiday3']['type']}}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{$homepage[$lang]['holiday3']['info']}}</p>
                        </div>
                    </li>
                    <li class="relative ">
                        <div class="group relative">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                              <img src="{{$homepage[$lang]['holiday4']['image']}}"  class="object-cover object-center">
                              
                              <div class="absolute inset-x-0    ">
                                <div aria-hidden="true" class="absolute  top-5 right-5 rounded-full bg-blue-900 h-24 w-24 text-center pt-4">
                                    <p class="relative text-lg font-semibold text-white ">apd <br/>€{{$homepage[$lang]['holiday4']['price']}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between space-x-8 text-base font-medium text-gray-900">
                              <h3>
                                <a wire:click="openHolidayModal('holiday4')">
                                  <span aria-hidden="true" class="absolute inset-0"></span>
                                  {{$homepage[$lang]['holiday4']['title']}}
                                </a>
                              </h3>
                              <p>{{$homepage[$lang]['holiday4']['type']}}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{$homepage[$lang]['holiday4']['info']}}</p>
                        </div>
                    </li>
                    <li class="relative ">
                        <div class="group relative">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                              <img src="{{$homepage[$lang]['holiday5']['image']}}"  class="object-cover object-center">
                              
                              <div class="absolute inset-x-0    ">
                                <div aria-hidden="true" class="absolute  top-5 right-5 rounded-full bg-blue-900 h-24 w-24 text-center pt-4">
                                    <p class="relative text-lg font-semibold text-white ">apd <br/>€{{$homepage[$lang]['holiday5']['price']}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between space-x-8 text-base font-medium text-gray-900">
                              <h3>
                                <a wire:click="openHolidayModal('holiday5')">
                                  <span aria-hidden="true" class="absolute inset-0"></span>
                                  {{$homepage[$lang]['holiday5']['title']}}
                                </a>
                              </h3>
                              <p>{{$homepage[$lang]['holiday5']['type']}}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{$homepage[$lang]['holiday5']['info']}}</p>
                        </div>
                    </li>
                    <li class="relative ">
                        <div class="group relative">
                            <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                              <img src="{{$homepage[$lang]['holiday6']['image']}}"  class="object-cover object-center">
                              
                              <div class="absolute inset-x-0    ">
                                <div aria-hidden="true" class="absolute  top-5 right-5 rounded-full bg-blue-900 h-24 w-24 text-center pt-4">
                                    <p class="relative text-lg font-semibold text-white ">apd <br/>€{{$homepage[$lang]['holiday6']['price']}}</p>
                                </div>
                              </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between space-x-8 text-base font-medium text-gray-900">
                              <h3>
                                <a wire:click="openHolidayModal('holiday6')">
                                  <span aria-hidden="true" class="absolute inset-0"></span>
                                  {{$homepage[$lang]['holiday6']['title']}}
                                </a>
                              </h3>
                              <p>{{$homepage[$lang]['holiday6']['type']}}</p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{$homepage[$lang]['holiday6']['info']}}</p>
                        </div>
                    </li>
                    <!-- More files... -->
                    </ul>
            </div>
            <div class="mt-5">
                <h2 class="text-xl font-semibold text-gray-900 pb-5">Articles</h2>
                <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-3 xl:gap-x-8">
                    <li class="relative h-64">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('post1')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['post1']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['post1']['title']}}</h2>
                            </div>
                        </div>
                    </li>
                    <li class="relative h-64">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('post2')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['post2']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['post2']['title']}}</h2>
                            </div>
                        </div>
                    </li>
                    <li class="relative h-64">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('post3')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['post3']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['post3']['title']}}</h2>
                            </div>
                        </div>
                    </li>
                    <li class="relative h-64">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('post4')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['post4']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['post4']['title']}}</h2>
                            </div>
                        </div>
                    </li>
                    <li class="relative h-64">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('post5')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['post5']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['post5']['title']}}</h2>
                            </div>
                        </div>
                    </li>
                    <li class="relative h-64">
                        <div class="flex relative h-full w-full   content-end  items-stretch">
                            <a  wire:click="openPostModal('post6')">
                                <img class="absolute h-full w-full rounded-lg object-cover object-center" src="{{$homepage[$lang]['post6']['image']}}" />
                            </a>
                            <div class="relative flex w-full flex-col items-start   bg-black bg-opacity-40 p-3   self-end rounded-b-lg">
                                <h2 class="text-lg font-medium text-white text-opacity-75">{{$homepage[$lang]['post6']['title']}}</h2>
                            </div>
                        </div>
                    </li>
                    <!-- More files... -->
                    </ul>
            </div>
            <!-- Save User Modal -->
            <form wire:submit.prevent="save">
                <x-modal.dialog wire:model.defer="showPostModal">
                    <x-slot name="title">Select a Wordpress post</x-slot>
        
                    <x-slot name="content">
                        <x-input.group  for="searchPost" label="Wordpress post">
                            <x-input.autocomplete 
                                wire:model.debounce.450ms="postSearch" 
                                wire:keyup.debounce.450ms="postsResult"
                                id="searchPost" 
                                name="searchPost" 
                                wireModel="postId" 
                                :rows="$posts" 
                                placeholder="Find a post" >
                                <x-slot:icon>
                                <x-icon.map class="flex-shrink-0 text-indigo-600"/>
                                </x-slot:icon>
                            </x-input.autocomplete>
                        </x-input.group>
                    </x-slot>
        
                    <x-slot name="footer">
                        <x-button.secondary wire:click="$set('showPostModal', false)">Cancel</x-button.secondary>
                    </x-slot>
                </x-modal.dialog>
            </form>
            <!-- Save User Modal -->
            <form wire:submit.prevent="save">
                <x-modal.dialog wire:model.defer="showHolidayModal">
                    <x-slot name="title">Select a holiday</x-slot>
        
                    <x-slot name="content">
                        <x-input.group  for="searchHoliday" label="find holiday">
                            <x-input.autocomplete 
                                wire:model.debounce.450ms="holidaySearch" 
                                wire:keyup.debounce.450ms="holidaysResult"
                                id="searchHoliday" 
                                name="searchHoliday" 
                                wireModel="holidayId" 
                                :rows="$holidays" 
                                placeholder="Find a holiday" >
                                <x-slot:icon>
                                <x-icon.palm class="flex-shrink-0 text-indigo-600"/>
                                </x-slot:icon>
                        </x-input.autocomplete>
                        </x-input.group>
                    </x-slot>
        
                    <x-slot name="footer">
                        <x-button.secondary wire:click="$set('showHolidayModal', false)">Cancel</x-button.secondary>
                    </x-slot>
                </x-modal.dialog>
            </form>
      </div>
    
</div>