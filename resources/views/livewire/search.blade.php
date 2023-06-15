<div>
    <div  x-data="{ openSection: false }" :class="{'h-40': openSection == true,'h-14': openSection == false}" 
        class="sm:space-x-0 h-14 transition-all ease-in-out duration-500 fixed bottom-0  z-50 sm:relative sm:h-auto border-b border-none pb-1 sm:flex sm:items-center sm:justify-center py-2 bg-white w-full mr-0 ml-0">
        <div class="mt-1 sm:ml-1 sm:mt-0">
            <div class="flex flex-col sm:flex-row rounded-md   space-x-2 sm:space-x-0">
              <div class="flex flex-row sm:flex-row rounded-md ">
                <div class="relative flex-grow focus-within:z-10  max-w-md mr-3 sm:mr-0">
                  <x-input.location 
                      wire:model.debounce.450ms="locationSearch" 
                      wire:keyup.debounce.450ms="locationsResult"
                      id="serachLocation" 
                      name="serachLocation" 
                      wireModel="locationId" 
                      :rows="$locations" 
                      class="py-2 rounded sm:rounded-none sm:rounded-l-lg border-r-none ml-2 sm:ml-0 "
                      placeholder="Où partez-vous ? Référence ?" >
                      <x-slot:icon>
                        <x-icon.map class="flex-shrink-0 text-sky-600"/>
                      </x-slot:icon>
                  </x-input.location>
                </div>
                <button @click="openSection = !openSection" type="button" class="w-8 mr-3 sm:mr-0 sm:hidden rounded-full bg-slate-400 p-1 my-1 px-1.5 text-white shadow-sm hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  <template x-if="openSection === true">
                    <x-icon.dup class="text-white" />
                  </template>
                  <template x-if="openSection === false">
                      <x-icon.ddown class="text-white" />
                  </template>
                </button>
              </div>
              <div class="flex flex-row space-x-2 sm:space-x-0">
                <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left z-10 sm:ml-0 my-2 sm:my-0">
                  <div >
                      <span class="shadow-sm">
                          <button @click="open = !open" type="button" class="rounded-md sm:rounded-none bg-white     relative  inline-flex items-center gap-x-1.5  px-3 py-2.5 text-sm  text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
                              <x-icon.calendar class="text-sky-500"/>
                                {{$dateFrom ? $dateFrom." - ".$dateTo : 'Quand voulez-vous partir?'}}
                              <x-icon.ddown class="text-sky-500" />
                          </button>
                      </span>
                  </div>
              
                  <div x-show="open" 
                      style="display: none;" 
                      x-description="Dropdown panel, show/hide based on dropdown state." 
                      x-transition:enter="transition ease-out duration-200" 
                      x-transition:enter-start="opacity-0 translate-y-1" 
                      x-transition:enter-end="opacity-100 translate-y-0" 
                      x-transition:leave="transition ease-in duration-150" 
                      x-transition:leave-start="opacity-100 translate-y-0" 
                      x-transition:leave-end="opacity-0 translate-y-1" 
                      class="absolute ml-0 -left-2 sm:left-1/2 mt-5 flex  w-screen max-w-max  sm:-translate-x-1/2 px-1 sm:px-4 z-10 bottom-11 sm:-bottom-100 mr-5">
                      <div class="w-screen max-w-2xl flex-auto overflow-hidden rounded-3xl bg-white text-sm leading-6 shadow-lg ring-1 ring-gray-900/5">
                          <div x-data="calendar()" x-init="[initDate(), getNoOfDays(),getNoOfDaysOfNextMonth(),period='{{$period}}']" x-cloak class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div class="shadow absolute right-0 -top-4 w-10 h-10 rounded-full bg-sky-200 hover:bg-sky-300 text-white hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
                              x-on:click="open = false">
                              <svg class="fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path
                                  d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z" />
                              </svg>
                            </div>
                            <div class=" mx-auto px-4 py-2 ">
                              <div class="bg-white rounded-lg  overflow-hidden">
                                <div class="hidden sm:flex flex-row space-x-2">
                                  <button @click="period='weekend'; first_day_period=false;selectedDays=[];"   :class="{'bg-sky-500': period === 'weekend'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Weekend</button>
                                  <button @click="period='long_weekend'; first_day_period=false;selectedDays=[];" :class="{'bg-sky-500': period === 'long_weekend'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Long weekend</button>
                                  <button @click="period='mid_week'; first_day_period=false;selectedDays=[]; " :class="{'bg-sky-500': period === 'mid_week'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Mid-week</button>
                                  <button @click="period='week'; first_day_period=false;selectedDays=[];" :class="{'bg-sky-500': period === 'week'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Semaine</button>
                                  <button @click="period='2weeks'; first_day_period=false;selectedDays=[];" :class="{'bg-sky-500': period === '2weeks'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">2 semaines</button>
                                </div>
                                <div class="flex items-center justify-between py-4 px-6">
                                  <div>
                                    <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                    <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                  </div>
                                  <div class="hidden sm:block">
                                    <span x-text="MONTH_NAMES[next_month]" class="text-lg font-bold text-gray-800"></span>
                                    <span x-text="next_year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                  </div>
                                  <div class="border rounded-lg px-1" style="padding-top: 2px;">
                                    <button 
                                      type="button"
                                      class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center" 
                                      :class="{'cursor-not-allowed opacity-25': isThisMonth() }"
                                      :disabled="isThisMonth() ? true : false"
                                      @click="previousMonth(); getNoOfDays();getNoOfDaysOfNextMonth()">
                                      <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                      </svg>  
                                    </button>
                                    <div class="border-r inline-flex h-6"></div>		
                                    <button 
                                      type="button"
                                      class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex items-center cursor-pointer hover:bg-gray-200 p-1" 
                                      @click="nextMonth(); getNoOfDays();getNoOfDaysOfNextMonth()">
                                      <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                      </svg>									  
                                    </button>
                                  </div>
                                </div>	
                        
                                <div class="flex flex-row space-x-2">
                                  <div>
                                    <div class="flex flex-wrap mb-0" >
                                      <template x-for="(day, index) in DAYS" :key="index">	
                                        <div style="width: 14.28%" class="px-2 py-2">
                                          <div
                                            x-text="day" 
                                            class="text-gray-600 text-sm uppercase tracking-wide font-bold text-center"></div>
                                        </div>
                                      </template>
                                    </div>
                          
                                    <div class="flex flex-wrap ">
                                      <template x-for="blankday in blankdays">
                                        <div 
                                          style="width: 14.28%; height: 35px"
                                          class="text-center border-none px-4 pt-2"	
                                        ></div>
                                      </template>	
                                      <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">	
                                        <div style="width: 14.28%; height: 35px" class="px-2 py-1 border-t border-b border-white  relative "
                                        :class="{
                                          'rounded-l-full': isFirst(date,month,year) == true ,
                                          'rounded-r-full': isLast(date,month,year) == true ,
                                          'bg-sky-200 ': isPeriod(date,month,year) == true ,
                                          }">
                                          <button
                                            @click="selectPeriod(date,month,year)"
                                            x-text="date"
                                            class="inline-flex w-6 h-6 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100"
                                            :class="{
                                              'bg-blue-500 text-white': isToday(date) == true, 
                                              'bg-white text-gray-700 hover:bg-blue-800 hover:text-white': isFirst(date,month,year) === true,
                                              'bg-blue-800 text-white': isSelectedPeriod(date,month,year) === true 
                                              }"	
                                            :disabled="isFirst(date,month,year) ? false : true"
                                          ></button>
                                        </div>
                                      </template>
                                    </div>
                                  </div>
                                  <div class="hidden sm:block">
                                    <div class="flex flex-wrap mb-0" >
                                      <template x-for="(day, index) in DAYS" :key="index">	
                                        <div style="width: 14.28%" class="px-2 py-2">
                                          <div
                                            x-text="day" 
                                            class="text-gray-600 text-sm uppercase tracking-wide font-bold text-center"></div>
                                        </div>
                                      </template>
                                    </div>
                          
                                    <div class="flex flex-wrap ">
                                      <template x-for="blankday in blankdays_of_next_month">
                                        <div 
                                          style="width: 14.28%; height: 35px"
                                          class="text-center border-none px-4 pt-2"	
                                        ></div>
                                      </template>	
                                      <template x-for="(next_date, dateIndex) in no_of_days_of_next_month" :key="dateIndex">	
                                        <div style="width: 14.28%; height: 35px" class="px-2 py-1 border-t border-b border-white relative" 
                                        :class="{
                                          'rounded-l-full': isFirst(next_date,next_month,next_year) == true ,
                                          'rounded-r-full': isLast(next_date,next_month,next_year) == true ,
                                          'bg-sky-200': isPeriod(next_date,next_month,next_year) == true ,
                                          }">
                                          <button
                                          @click="selectPeriod(next_date,next_month,next_year)"
                                            x-text="next_date"
                                            class="inline-flex w-6 h-6 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100"
                                            :class="{
                                              'bg-blue-500 text-white': isToday(next_date,next_month) == true, 
                                              'bg-white text-gray-700 hover:bg-blue-800 hover:text-white': isFirst(next_date,next_month,next_year) == true,
                                              'bg-blue-800 text-white': isSelectedPeriod(next_date,next_month,next_year) === true 
                                              }"	
                                              :disabled="isFirst(next_date,next_month,next_year) ? false : true"
                                          ></button>
                                        </div>
                                      </template>
                                    </div>
                                  </div>
                                </div>
                                <div class="flex flex-row space-x-2 justify-end">
                                  <x-button.secondary class="border-pink-500 px-4" @click="cleanPeriod()" >J'efface</x-button.primary>
                                  <x-button.primary  @click="validatePeriod();open=false">Je valide</x-button.primary>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class=" sm:mx-0">
                  <div class="relative  rounded-none shadow-sm my-2 sm:my-0 ">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 border-l-0">
                      <x-icon.users class="h-5 w-5 text-blue-400" />
                    </div>
                    <input wire:model.lazy="numberPeople" class="w-32 block   rounded-md sm:rounded-none rounded-r-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Vacanciers">
                  </div>
                </div>
              </div>
              
            </div>
        </div>
        <div class="space-x-2 sm:space-x-0 sm:ml-4 sm:mt-0 flex flex-row justify-center ">

          <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
            <div>
                <span class="shadow-sm">
                    <button @click="open = !open" type="button" class="rounded-md sm:rounded-none sm:border-l-0  relative  inline-flex items-center gap-x-1.5  px-3 py-2.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
                        <x-icon.sort />
                          Trier par
                        <x-icon.ddown />
                    </button>
                </span>
            </div>
        
            <div x-show="open" 
                style="display: none;" 
                x-description="Dropdown panel, show/hide based on dropdown state." 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 translate-y-1" 
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-150" 
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 translate-y-1" 
                class="origin-top-right absolute left-0 sm:right-0 bottom-11 sm:-bottom-32   mt-2 -mr-1  rounded-md shadow-lg z-20">
                <div class="rounded-md bg-white shadow-xs">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                      <button @click="open = false" wire:click="$emit('setOrder',{'field':'id', 'direction':'desc'})" class="text-left block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        <x-icon.new /> Récent
                      </button>
                      <button @click="open = false" wire:click="$emit('setOrder',{'field':'week_price', 'direction':'asc'})" class="text-left block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        <x-icon.arrowup /> Prix asc
                      </button>
                      <button @click="open = false" wire:click="$emit('setOrder',{'field':'week_price', 'direction':'desc'})" class="text-left block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        <x-icon.arrowdown /> Prix desc
                      </button>
                    </div>
                </div>
            </div>
          </div>
          <div class="flex rounded-md shadow-sm mr-5">
            <button @click="open = !open" type="button" class="relative  inline-flex items-center gap-x-1.5 rounded-md sm:rounded-none sm:rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              <span class="font-bold text-gray-500"> Plus de filtres </span> <x-icon.filters />
            </button>
          </div>
        </div>
    </div>
</div>