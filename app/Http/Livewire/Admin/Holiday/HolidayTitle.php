<?php

namespace App\Http\Livewire\Admin\Holiday;

use App\Models\HolidayTitle as HolidayTitleModel;
use Livewire\Component;
use Illuminate\Support\Str;
class HolidayTitle extends Component
{
    public HolidayTitleModel $holidayTitle;
    public $lang;

    protected $listeners = [
        'refreshContent' => 'refreshTitle',
        'saveContent' => 'save',
        'setTitle' => 'setTitle',
        'isTitleValid' => 'isTitleValid'];

    protected $rules = [
      //  'holidayTitle.holiday_id' => 'required',
        'holidayTitle.name' => 'required',
        'holidayTitle.lang' => 'required',
        'holidayTitle.slug' => 'required',
        'holidayTitle.privilege' => 'sometimes',
    ];
    public function isTitleValid() {
        $this->validate();
    }
    public function mount(HolidayTitleModel $holidayTitle, $lang)
    {
        $this->lang = $lang;
        $this->holidayTitle = $holidayTitle;
    }
    
    public function save($holidayId)
    {
        $this->holidayTitle->holiday_id = $holidayId;
        $this->validate();
        $this->holidayTitle->save();
    }
    public function refreshTitle($lang)
    {
        $this->lang = $lang;
    }

    public function setTitle($lang, $title)
    {
        $this->holidayTitle->slug = Str::slug($title);
    }

    public function render()
    {
        return view('livewire.admin.holiday.holiday-title');
    }

}
