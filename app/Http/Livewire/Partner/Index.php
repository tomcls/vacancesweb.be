<?php

namespace App\Http\Livewire\Partner;

use App\Data\WordPress\PostData;
use App\Models\Holiday;
use App\Models\HolidayTitle;
use App\Models\PartnerArticle;
use App\Models\PartnerCatalog;
use App\Models\PartnerHoliday;
use App\Models\PartnerHome;
use App\Repositories\ArticleRepository;
use Livewire\Component;
use Illuminate\Support\Facades\App;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Index extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public PartnerCatalog $partnerCatalog;
    public $slug;
    public $lang;
    public $catalog = [];
    public $holidays = [];
    public $posts = [];
    public $hero;
    private PostData $post;
    public $holiday;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->lang = App::currentLocale();
        $repo = new ArticleRepository();

        $this->hero = PartnerHome::hero($slug,$this->lang)->first();
        if($this->hero->hero_type =='article') {
            $this->post = $repo->getPostById($this->hero->hero_id);
        } else {
            $this->holiday = HolidayTitle::whereHolidayId($this->hero->hero_id)->whereLang($this->lang)->first();
        }
        $this->catalog = PartnerCatalog::partnerHolidays($slug)->get();
        $this->holidays = PartnerHoliday::partnerHolidays($slug)->get();
        $posts = PartnerArticle::partnerPosts($slug)->get()->pluck('post_id');
        
        
        $this->posts = $repo->getPostsByIds($posts->toArray());
    }
    // public function dehydrateRows() {
    //     logger("dehydrateRows");
    // }
    // public function updatedRows() {
    //     logger("updatedRows");
    // }

    public function render()
    {
        return view('livewire.partner.index',['post'=> $this->post??null])->layout('layouts.base');
    }
}
