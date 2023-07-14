<?php

namespace App\Http\Livewire\Partner;

use App\Models\PartnerArticle;
use App\Models\PartnerHoliday;
use App\Repositories\ArticleRepository;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithPerPagePagination;
use App\Traits\DataTable\WithSorting;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Holidays extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;
    public $slug;
    public $lang;
    public $catalog = [];
    public $holidays = [];
    public $posts = [];
    public $type;

    public function mount($slug,$type)
    {
        $this->slug = $slug;
        $this->type = $type;
        $this->lang = App::currentLocale();
        $this->sorts = ['sort'=>'asc'];

        $posts = PartnerArticle::partnerPosts($slug)->get()->pluck('post_id');
        $repo = new ArticleRepository();
        $this->posts = $repo->getPostsByIds($posts->toArray());
        
        logger('mount');
    }

    public function dehydrate()
    {
        $this->emit('loadImages');
    }
    public function getRowsQueryProperty()
    {
        $query = PartnerHoliday::partnerHolidays($this->slug,$this->type);
        return $this->applySorting($query);
    }
    public function getRowsProperty() {
        return $this->applyPagination($this->rowsQuery);
    }
    public function render()
    {
        return view('livewire.partner.holidays',['rows'=>$this->rows])->layout('layouts.base');
    }
}
