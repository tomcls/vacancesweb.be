// import suneditor from 'suneditor'
// import plugins from 'suneditor/src/plugins'
// window.suneditor = suneditor;
// window.plugins = plugins;


export default function calendar() {
    return {
        MONTH_NAMES : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        DAYS :['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        day_selected: null,
        month_selected: null,
        year_selected: null,
        selectedDays: [],
        first_day_period: false,
        period: 'weekend',
        month: '',
        next_month: '',
        year: '',
        no_of_days: [],
        no_of_days_of_next_month: [],
        blankdays_of_next_month: [],
        days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        validatePeriod() {
            Livewire.emit('setPeriod', {'days':this.selectedDays,'period':this.period});
        },
        cleanPeriod() {
            this.selectedDays = [];
            this.day_selected = null;
            this.month_selected;
            this.year_selected = null;
            Livewire.emit('cleanPeriod');
        },
        initDate() {
            let today = new Date();
            this.month = today.getMonth();
            this.year = today.getFullYear();
            this.next_year = today.getFullYear();
            this.next_month = new Date(this.year, this.month + 2, 0).getMonth();
            this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
        },

        isToday(date) {
            const today = new Date();
            const d = new Date(this.year, this.month, date);
            return today.toDateString() === d.toDateString() ? false : false;
        },
        isThisMonth() {
            const today = new Date();
            const d = new Date(this.year, this.month);
            return today.getMonth() === d.getMonth() && this.year === today.getFullYear() ? true : false;
        },
        getNoOfDays() {
            let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
            // find where to start calendar day of week
            let dayOfWeek = new Date(this.year, this.month).getDay();
            let blankdaysArray = [];
            for (var i = 1; i < dayOfWeek; i++) {
                blankdaysArray.push(i);
            }
            let daysArray = [];
            for (var i = 1; i <= daysInMonth; i++) {
                daysArray.push(i);
            }
            this.blankdays = blankdaysArray;
            this.no_of_days = daysArray;
        },
        getNoOfDaysOfNextMonth() {
            let daysInMonth = new Date(this.year, this.month + 2, 0).getDate();
            // find where to start calendar day of week
            let dayOfWeek = new Date(this.year, this.month + 1).getDay();
            let blankdaysArray = [];
            for (var i = 1; i < dayOfWeek; i++) {
                blankdaysArray.push(i);
            }
            let daysArray = [];
            for (var i = 1; i <= daysInMonth; i++) {
                daysArray.push(i);
            }
            this.blankdays_of_next_month = blankdaysArray;
            this.no_of_days_of_next_month = daysArray;
        },
        nextMonth() {
            if (this.month == 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
            if (this.next_month == 11) {
                this.next_month = 0;
                this.next_year++;
            } else {
                this.next_month++;
            }
        },
        previousMonth() {
            if (this.month == 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
            if (this.next_month == 0) {
                this.next_month = 11;
                this.next_year--;
            } else {
                this.next_month--;
            }
        },
        isPeriod(day, month, year) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const d = new Date(year, month, day);
            switch (this.period) {
                case 'weekend':
                    return [5, 6, 0].includes(d.getDay()) && d >= today && this.first_day_period ? true : false;
                    break;
                case 'long_weekend':
                    return [5, 6, 0, 1].includes(d.getDay()) && d >= today && this.first_day_period ? true : false;
                    break;
                case 'mid_week':
                    return ([1, 2, 3, 4, 5].includes(d.getDay()) && d >= today && this.first_day_period === true) ? true : false;
                    break;
                case 'week':
                    return [0, 1, 2, 3, 4, 5, 6].includes(d.getDay()) && d >= today ? true : false;
                    break;
                case '2weeks':
                    return [0, 1, 2, 3, 4, 5, 6].includes(d.getDay()) && d >= today ? true : false;
                    break;

                default:
                    break;
            }
        },
        isFirst(day, month, year) {
            const d = new Date(year, month, day);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            d.setHours(0, 0, 0)

            switch (this.period) {
                case 'weekend':
                    if (d.getDay() === 5 && d >= today) {
                        this.first_day_period = true;
                        return true;
                    }
                    return false;
                    break;
                case 'long_weekend':
                    if (d.getDay() === 5 && d >= today) {
                        this.first_day_period = true;
                        return true;
                    }
                    return false;
                    break;
                case 'mid_week':
                    if (d.getDay() === 1 && d >= today) {
                        this.first_day_period = true;
                        return true;
                    }
                    return false;
                    break;
                case 'week':
                    if (d.getDay() === 5 && d >= today && this.first_day_period === false) {
                        this.first_day_period = true;
                        return true;
                    }
                    return false;
                    break;
                case '2weeks':
                    if (d.getDay() === 5 && d >= today && this.first_day_period === false) {
                        this.first_day_period = true;
                        return true;
                    }
                    return false;
                    break;
                default:
                    break;
            }
        },
        isLast(day, month, year) {
            const d = new Date(year, month, day);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            switch (this.period) {
                case 'weekend':
                    return (d.getDay() == 0 && d >= today) ? true : false;
                    break;
                case 'long_weekend':
                    return (d.getDay() === 1 && d >= today) ? true : false;
                    break;
                case 'mid_week':
                    return (d.getDay() === 5 && d >= today) ? true : false;
                    break;

                default:
                    break;
            }
        },
        selectPeriod(day, month, year) {
            const d = new Date(year, month, day);
            this.day_selected = day;
            this.month_selected = month;
            this.year_selected = year;
            this.selectedDays = [];
            let datePeriodSelected = new Date(this.year_selected, this.month_selected, this.day_selected);
            datePeriodSelected.setHours(0, 0, 0);
            this.selectedDays.push(new Date(datePeriodSelected));
            let i = 0;
            switch (this.period) {
                case 'weekend':
                    i = 3
                    break;
                case 'long_weekend':
                    i = 4
                    break;
                case 'mid_week':
                    i = 5
                    break;
                case 'week':
                    i = 7
                    break;
                case '2weeks':
                    i = 14
                    break;
                default:
                    break;
            }
            for (let index = 1; index < i; index++) {
                let r = new Date(datePeriodSelected.setDate(datePeriodSelected.getDate() + 1))
                this.selectedDays.push(r);
            }
        },
        isSelectedPeriod(day, month, year) {
            const d = new Date(year, month, day);
            if (this.selectedDays.length > 0) {
                for (let index = 1; index < this.selectedDays.length; index++) {
                    if (new Date(this.selectedDays[index]).getTime() === d.getTime()) {
                        return true;
                    }
                }
            }
        }
    }
}

window.calendar = calendar;