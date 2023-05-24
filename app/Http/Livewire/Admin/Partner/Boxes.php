<?php

namespace App\Http\Livewire\Admin\Partner;

use Livewire\Component;
use App\Models\Partner;
use App\Models\PartnerBoxe;
use App\Models\HolidayTitle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Traits\DataTable\WithSorting;
use App\Traits\Wordpress\WithWordpress;
use App\Repositories\ArticleRepository;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Boxes extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithWordpress;

    public PartnerBoxe $editing;

    public $showDeleteModal = false;
    public $showEditModal = false;

    public $partners;

    public $posts = null;
    public $postSearch = null;
    public $postId = null;

    public $holidays = null;
    public $holidaySearch = null;
    public $holidayId = null;

    public $lang ;
    public $upload;

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem'];

    public $rules = [
        'editing.partner_id' => 'required',
        'editing.box_id' => 'required',
        'editing.box_type' => 'required',
    ];

    public function mount() {
        $this->editing = $this->makeBlankBox();
        $this->partners = Partner::get();
        $this->lang = App::currentLocale();
    }
    public function makeBlankBox()
    {
        return PartnerBoxe::make([]);
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' boxes','type'=>'success']);
    }

    public function getRowsQueryProperty()
    {
        $query = PartnerBoxe::query();

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        /* return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });*/
    }
    public function holidaysResult()
    {
        if ($this->holidaySearch) {
            $query = HolidayTitle::query()->select(DB::raw("holiday_id as id, name AS title, slug as subtitle"))
                ->when($this->holidaySearch, fn ($query, $name) => $query
                    ->where('name', 'like', '%' . $name . '%')
                    ->orWhere('holiday_id', 'like', '%' . $name . '%'))->limit(5);
            $this->holidays = $query->get();
        }
    }
    // Fetch records
    public function postsResult()
    {
        if ($this->postSearch) {
            $wordpress = new ArticleRepository();
            $suggestions = $wordpress->suggest([
                'search' => urlencode($this->postSearch),
                'lang' => $this->lang,
            ]);
            $this->posts = $this->formatArticleSuggestions($suggestions);
        }
    }
    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankBox();

        $this->showEditModal = true;
    }

    public function edit(PartnerBoxe $hero)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($hero)){
            $this->editing = $hero;
            if($this->editing->hero_type == 'holiday') {
                $this->holidaySearch = $this->editing->box_id;
            } else {
                $this->postSearch = $this->editing->box_id;
            }
        } 
        $this->showEditModal = true;
    }
    
    public function setAutoCompleteItem($type, $text, $id)
    {
        switch ($type) {
            case 'holidayId':
                $holiday = HolidayTitle::whereHolidayId($id)->whereLang($this->lang)->first();
                
                $this->editing->box_id = $id;
                $this->holidaySearch = $holiday->holiday->id .'# '.$holiday->name;
                break;
            default:
                $wordpress  = new ArticleRepository();
                $post = $wordpress->getPostById($id);
                
                $this->editing->box_id = $id;
                
                $this->postSearch = $post->postId .'# '.$post->title;
                break;
        }
    }

    public function save()
    {
        $h = PartnerBoxe::whereBoxType($this->editing->box_type)->whereBoxId($this->editing->box_id)->wherePartnerId($this->editing->partner_id)->first();
        logger($h);
        if($h && $h->id) {
            return $this->notify(['message' => 'Boxe already exist', 'type' => 'alert']);
        }
        $this->validate();

        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'Boxe well saved', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.admin.partner.boxes', [
            'boxes' => $this->rows,
        ])->layout('layouts.admin');
    }
}
