<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\HolidayTitle;
use Illuminate\Support\Facades\DB;
use App\Models\HolidayTypeTranslation;
use App\Repositories\ArticleRepository;
use App\Traits\Wordpress\WithWordpress;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Homepage extends Component
{
    use WithWordpress;

    public $posts = [];
    public $holidays = [];
    public $postId;
    public $postSearch;
    public $holidaySearch;
    public $type; // 'article' or 'reportage'
    public $lang = null;

    public $homepage = [];

    public $heroItem;
    public $holidayItem;
    public $showPostModal = false;
    public $showHolidayModal = false;

    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
    ];
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

    public function mount()
    {
        $this->lang = App::currentLocale();
        $json = json_decode(Storage::get("homepage.json"), true);
        $this->homepage = $json;
    }

    public function setAutoCompleteItem($type, $text, $id)
    {
        switch ($type) {
            case 'holidayId':
                $holiday = HolidayTitle::whereHolidayId($id)->whereLang($this->lang)->first();
                $object = [
                    'image' => $holiday->holiday->cover->url('small'),
                    'title' => $holiday->name,
                    'url' => 'https//www.vacancesweb.be/vacances/detail/' . $holiday->slug,
                    'price' => $holiday->holiday->lowestPrice->price_customer??null,
                    'promo' => $holiday->holiday->lowestPrice->discount??null,
                    'type' => HolidayTypeTranslation::whereHolidayTypeId($holiday->holiday->holiday_type_id)->whereLang($this->lang)->first()->name,
                    'info' => $holiday->privilege
                ];
                $this->showHolidayModal = false;
                $this->homepage[$this->lang][$this->holidayItem] = $object;
                $this->holidaySearch = $holiday->holiday->id .'# '.$holiday->name;
                break;

            default:

                $wordpress  = new ArticleRepository();
                $post = $wordpress->getPostById($id);
                $object = [
                    'image' => $post->cover,
                    'title' => $post->title,
                    'url' => 'https//www.vacancesweb.be/articles/' . $post->slug,
                ];
                $this->homepage[$this->lang][$this->heroItem] = $object;
                $this->showPostModal = false;
                $this->postSearch = $post->postId .'# '.$post->title;
                break;
        }
    }
    public function save()
    {
        Storage::put('homepage.json', json_encode($this->homepage));
        $this->notify(['message' => 'Homepage well saved', 'type' => 'success']);
    }
    public function openPostModal($heroItem)
    {
        $this->heroItem = $heroItem;
        $this->showPostModal = true;
        $this->postSearch = null;
        $this->holidaySearch = null;
        $this->posts = [];
    }
    public function openHolidayModal($item)
    {
        $this->holidayItem = $item;
        $this->showHolidayModal = true;
        $this->postSearch = null;
        $this->holidaySearch = null;
        $this->holidays = [];
    }

    public function render()
    {
        return view('livewire.admin.homepage')->layout('layouts.admin');
    }
}
