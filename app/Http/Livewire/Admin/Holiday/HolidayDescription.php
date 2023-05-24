<?php

namespace App\Http\Livewire\Admin\Holiday;
use App\Models\HolidayDescription as HolidayDescriptionModel;
use Livewire\Component;

class HolidayDescription extends Component
{ 
    public HolidayDescriptionModel $holidayDescription;
    public $lang;

    protected $listeners = ['setDescription' => 'setDescription','refreshContent' => 'refreshDescription','saveContent' => 'save'];

    protected $rules = [
        'holidayDescription.holiday_id' => 'required',
        'holidayDescription.description' => 'required',
        'holidayDescription.lang' => 'required',
    ];

    public function mount(HolidayDescriptionModel $holidayDescription, $lang)
    {
        $this->lang = $lang;
        $this->holidayDescription = $holidayDescription;
    }
    
    public function save($holidayId)
    {
        $this->holidayDescription->holiday_id = $holidayId;
        $this->validate();
        $this->holidayDescription->save();
    }
    public function refreshDescription($lang)
    {
        $this->lang = $lang;
    }
    public function setDescription($description)
    {
        $this->holidayDescription->description = $description;
    }
    public function render()
    {
        return view('livewire.admin.holiday.holiday-description');
    }
}
