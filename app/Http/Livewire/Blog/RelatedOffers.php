<?php

namespace App\Http\Livewire\Blog;

use App\Models\HolidayRegion;
use App\Models\HouseRegion;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class RelatedOffers extends Component
{
    public $houses = [];
    public $holidays = [];
    public $lang = 'fr';

    public function mount(array $tags = null, $lang = 'fr')
    {
        $this->lang = App::currentLocale();
        if ($tags) {
            // get houses by region's slug (a tag is a region/country slug: marrakech, tunis, mice, maroc) 
            $houseRegion = new HouseRegion();
            $this->houses = $houseRegion->getHousesBySlugs($tags, $this->lang);
            $holidayRegion = new HolidayRegion();
            $this->holidays = $holidayRegion->getHolidaysBySlugs($tags, $this->lang);
        }
    }
    public function render()
    {
        return view('livewire.blog.related-offers');
    }
}
