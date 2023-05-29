<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\CostTranslation;
use App\Models\HouseCost;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class HouseCosts extends Component
{
    public $costs = [];
    public $houseCosts ;
    public $houseId = null;
    public $lang = null;
    public $costUnits = [];

    protected $rules = [
        'houseCosts.*.price' => 'required',
        'houseCosts.*.cost_unit' => 'required'
    ];

    public function mount($houseId)
    {
        $this->lang = App::currentLocale();
        $this->houseId = $houseId;
        $this->costs = CostTranslation::whereLang($this->lang)->get();
        $hc = HouseCost::whereHouseId($this->houseId)->get();//->mapWithKeys(function ($houseCost) {
        //     return ['cost_id_'.$houseCost['cost_id'] => $houseCost];
        // });
        foreach ($hc as  $value) {
            $this->houseCosts[$value->cost_id] = $value;
        }
    }
    public function save() {
        HouseCost::whereHouseId($this->houseId)->delete();
        $houseCosts = [];
        foreach ($this->houseCosts as $cost_id => $hc) {
            if($hc['price']) {
                array_push($houseCosts,['house_id'=>$this->houseId, 'cost_id'=>$cost_id, 'price'=>$hc['price'], 'cost_unit'=>$hc['cost_unit']??null]);
            }
        }
        if(count($houseCosts)) {
            HouseCost::insert($houseCosts);
        }
        $this->notify(['message'=>'Costs well saved','type'=>'success']);
    }
    public function render()
    {
        return view('livewire.admin.house.house-costs');
    }
}
