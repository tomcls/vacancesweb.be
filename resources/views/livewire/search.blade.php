<div>
    <div class="border-b border-none pb-1 sm:flex sm:items-start sm:justify-between py-2">
        <div class="mt-3 sm:ml-1 sm:mt-0">
            <label for="mobile-search-location" class="sr-only">Search</label>
            <label for="desktop-search-location" class="sr-only">Search</label>
            <div class="flex rounded-md shadow-sm">
              <div class="relative flex-grow focus-within:z-10 pl-1 max-w-md">
                <x-input.location 
                    wire:model.debounce.450ms="locationSearch" 
                    wire:keyup.debounce.450ms="locationsResult"
                    id="serachLocation" 
                    name="serachLocation" 
                    wireModel="locationId" 
                    :rows="$locations" 
                    class="py-2 rounded sm:rounded-none sm:rounded-l-lg border-r-none ml-2 sm:ml-0"
                    placeholder="Où partez-vous ? Référence ?" >
                    <x-slot:icon>
                      <x-icon.map class="flex-shrink-0 text-sky-600"/>
                    </x-slot:icon>
                </x-input.location>
              </div>
              <x-search.date  wire:model='dateFrom' placeHolder="Arrivé"  />
              <x-search.date  wire:model='dateTo' placeHolder="Départ" />
              <div class="hidden sm:block">
                <div class="relative  rounded-none shadow-sm border-l-0 border-r-0">
                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 border-l-0">
                    <x-icon.users class="h-5 w-5 text-blue-400" />
                  </div>
                  <input wire:model.lazy="numberPeople" class="block w-full border-l-0 border-r-0 rounded-none rounded-r-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="Nombre de vacanciers">
                </div>
              </div>
              <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left z-10">
                <div>
                    <span class="shadow-sm">
                        <button @click="open = !open" type="button" class="rounded-l ml-2 sm:ml-0  relative  inline-flex items-center gap-x-1.5  px-3 py-2.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
                            <x-icon.calendar class="text-sky-500"/>
                              Quand voulez-vous partir?
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
                    class="absolute left-1/2 z-10 mt-5 flex w-screen max-w-max -translate-x-1/2 px-4">
                    <div class="w-screen max-w-2xl flex-auto overflow-hidden rounded-3xl bg-white text-sm leading-6 shadow-lg ring-1 ring-gray-900/5">
                        <div x-data="app()" x-init="[initDate(), getNoOfDays(),getNoOfDaysOfNextMonth()]" x-cloak class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                          <div class=" mx-auto px-4 py-2 ">
                            <div class="bg-white rounded-lg  overflow-hidden">
                              <div class="flex flex-row space-x-2">
                                <button @click="period='weekend'; first_day_period=false"   :class="{'bg-sky-500': period === 'weekend'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Weekend</button>
                                <button @click="period='long_weekend'; first_day_period=false" :class="{'bg-sky-500': period === 'long_weekend'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Long weekend</button>
                                <button @click="period='mid_week'; first_day_period=false " :class="{'bg-sky-500': period === 'mid_week'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Mid-week</button>
                                <button @click="period='week'; first_day_period=false" :class="{'bg-sky-500': period === 'week'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Semaine</button>
                                <button @click="period='2weeks'; first_day_period=false" :class="{'bg-sky-500': period === '2weeks'}" class="rounded-full  px-2.5 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">2 semaines</button>
                              </div>
                              <div class="flex items-center justify-between py-4 px-6">
                                <div>
                                  <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                  <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                </div>
                                <div>
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
                                        'bg-sky-200 text-white': isPeriod(date,month,year) == true ,
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
                                        'bg-sky-200 text-white': isPeriod(next_date,next_month,next_year) == true ,
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
                                <x-button.secondary class="border-pink-500 px-4" >J'efface</x-button.primary>
                                <x-button.primary >Je valide</x-button.primary>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
        </div>
        <div class="mt-3 sm:ml-4 sm:mt-0 flex flex-row">

          <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left z-10">
            <div>
                <span class="shadow-sm">
                    <button @click="open = !open" type="button" class="rounded-l ml-2 sm:ml-0  relative  inline-flex items-center gap-x-1.5  px-3 py-2.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
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
                class="origin-top-right absolute right-0 mt-2  rounded-md shadow-lg ">
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
          <div class="flex rounded-md shadow-sm mr-2">
            <button @click="open = !open" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              <span class="font-bold text-gray-500"> Plus de filtres </span> <x-icon.filters />
            </button>
          </div>
        </div>
    </div>

<script>
  const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  const DAYS = [ 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun'];

  function app() {
    return {
      day_selected: null,
      month_selected: null,
      year_selected: null,
      selectedDays: [],
      first_day_period: false,
      period: 'weekend',
      month: '',
      next_month:'',
      year: '',
      no_of_days: [],
      no_of_days_of_next_month: [],
      blankdays_of_next_month: [],
      days: [ 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun'],
      initDate() {
        let today = new Date();
        this.month = today.getMonth();
        this.year = today.getFullYear();
        this.next_year = today.getFullYear();
        this.next_month = new Date(this.year, this.month + 2, 0).getMonth();
        this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
       // console.log(this.next_month, this.month)
      },

      isToday(date) {
        const today = new Date();
        const d = new Date(this.year, this.month, date);
       // console.log("today = ",today.getDay())
        return today.toDateString() === d.toDateString() ? false : false;
      },
      isThisMonth() {
        const today = new Date();
        const d = new Date(this.year, this.month);
        console.log(d.getDay())
        return today.getMonth() === d.getMonth() && this.year === today.getFullYear() ? true : false;
      },
      getNoOfDays() {
        let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
        // find where to start calendar day of week
        let dayOfWeek = new Date(this.year, this.month).getDay();
        let blankdaysArray = [];
        for ( var i=1; i < dayOfWeek; i++) {
          blankdaysArray.push(i);
        }
        let daysArray = [];
        for ( var i=1; i <= daysInMonth; i++) {
          daysArray.push(i);
        }
        this.blankdays = blankdaysArray;
        this.no_of_days = daysArray;
      },
      getNoOfDaysOfNextMonth() {
        let daysInMonth = new Date(this.year, this.month + 2, 0).getDate();
        // find where to start calendar day of week
        let dayOfWeek = new Date(this.year, this.month+1).getDay();
        let blankdaysArray = [];
        for ( var i=1; i < dayOfWeek; i++) {
          blankdaysArray.push(i);
        }
        let daysArray = [];
        for ( var i=1; i <= daysInMonth; i++) {
          daysArray.push(i);
        }
        this.blankdays_of_next_month = blankdaysArray;
        this.no_of_days_of_next_month = daysArray;
      },
      nextMonth() {
        if(this.month == 11) {
          this.month = 0;
          this.year++;
        } else {
          this.month++;
        }
        if(this.next_month == 11) {
          this.next_month = 0;
          this.next_year++;
        } else {
          this.next_month++;
        }
      },
      previousMonth() {
        if(this.month == 0) {
          this.month = 11;
          this.year--;
        } else {
          this.month--;
        }
        if(this.next_month == 0) {
          this.next_month = 11;
          this.next_year--;
        } else {
          this.next_month--;
        }
      },
      isPeriod(day,month,year) {
        const today = new Date();
        today.setHours(0,0,0,0);
        const d = new Date(year, month , day);
        switch (this.period) {
          case 'weekend':
            return [5,6,0].includes(d.getDay()) && d >= today && this.first_day_period ? true : false;
            break;
          case 'long_weekend':
            return [5,6,0,1].includes(d.getDay()) && d >= today  && this.first_day_period ? true : false;
            break;
          case 'mid_week':
            return ([1,2,3,4,5].includes(d.getDay()) && d >= today  && this.first_day_period === true) ? true : false;
            break;
          case 'week':
            return [0,1,2,3,4,5,6].includes(d.getDay()) && d >= today   ? true : false;
            break;
          case '2weeks':
            return [0,1,2,3,4,5,6].includes(d.getDay()) && d >= today   ? true : false;
            break;
        
          default:
            break;
        }
      },
      isFirst(day,month,year) {
        const d = new Date(year, month , day);
        const today = new Date();
        today.setHours(0,0,0,0);
        d.setHours(0,0,0)
        
        switch (this.period) {
          case 'weekend':
            if(d.getDay() === 5 && d >= today) {
              this.first_day_period = true;
              return true;
            }
            return false;
            break;
          case 'long_weekend':
            if(d.getDay() === 5 && d >= today ) {
              this.first_day_period = true;
              return true;
            }
            return false;
            break;
          case 'mid_week':
            if(d.getDay() === 1 && d >= today ) {
              this.first_day_period = true;
              return true;
            }
            return false;
            break;
          case 'week':
            if(d.getDay() === 5 && d >= today && this.first_day_period === false) {
              this.first_day_period = true;
              return true;
            }
            return false;
            break;
          case '2weeks':
            if(d.getDay() === 5 && d >= today && this.first_day_period === false) {
              this.first_day_period = true;
              return true;
            }
            return false;
            break;
          default:
            break;
        }
      },
      isLast(day,month,year) {
        const d = new Date(year, month , day);
        const today = new Date();
        today.setHours(0,0,0,0);
        switch (this.period) {
          case 'weekend':
            return (d.getDay()== 0 && d >= today) ? true : false;
            break;
          case 'long_weekend':
            return (d.getDay()=== 1  && d >= today ) ? true : false;
            break;
          case 'mid_week':
            return (d.getDay()=== 5  && d >= today) ? true : false;
            break;
        
          default:
            break;
        }
      },
      selectPeriod(day,month,year) {
        const d = new Date(year, month , day);
        this.day_selected = day;
        this.month_selected = month;
        this.year_selected = year;
        this.selectedDays = [];
        let datePeriodSelected = new Date(this.year_selected, this.month_selected , this.day_selected);
        datePeriodSelected.setHours(0,0,0);
        this.selectedDays.push(new Date(datePeriodSelected));
        let i = 0;
        switch (this.period) {
          case 'weekend':
            i=3
            break;
          case 'long_weekend':
            i=4
            break;
          case 'mid_week':
            i=5
            break;
          case 'week':
            i=7
            break;
          case '2weeks':
            i=14
            break;
          default:
            break;
        }
        for (let index = 1; index < i; index++) {
          let r = new Date(datePeriodSelected.setDate(datePeriodSelected.getDate() + 1))
          this.selectedDays.push(r);
        }
        console.log(this.selectedDays)
      },
      isSelectedPeriod(day,month,year) {
        const d = new Date(year, month , day);
        if(this.selectedDays.length>0) {
          for (let index = 1; index < this.selectedDays.length; index++) {
            if(new Date(this.selectedDays[index]).getTime() === d.getTime() ) {
              return true;
            }
          }
        }
      }
  }
}
</script>
</div>