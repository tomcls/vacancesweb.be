<?php

namespace App\Http\Livewire\Me\House;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\HouseTitle as HouseTitleModel;

class HouseTitle extends Component
{
    public HouseTitleModel $houseTitle;
    public $lang;

    protected $listeners = [
        'refreshContent' => 'refreshTitle',
        'saveContent' => 'save',
        'setTitle' => 'setTitle',
        'isTitleValid' => 'isTitleValid'];

    protected $rules = [
        'houseTitle.name' => 'required',
        'houseTitle.lang' => 'required',
        'houseTitle.slug' => 'required',
    ];
    public function isTitleValid() {
        $this->validate();
    }
    public function mount(HouseTitleModel $houseTitle, $lang)
    {
        $this->lang = $lang;
        $this->houseTitle = $houseTitle;
    }
    
    public function save($houseId)
    {
        $this->houseTitle->house_id = $houseId;
        $this->validate();
        $this->houseTitle->save();
    }
    public function refreshTitle($lang)
    {
        $this->lang = $lang;
    }

    public function setTitle($lang, $title)
    {
        $this->houseTitle->slug = Str::slug($title);
    }

    public function render()
    {
        return view('livewire.me.house.house-title');
    }
}
