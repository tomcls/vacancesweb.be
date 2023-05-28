<?php

namespace App\Http\Livewire\Admin\Partner;

use File;
use Image;
use Exception;
use Livewire\Component;
use App\Models\Partner;
use App\Models\PartnerHome;
use Illuminate\Support\Str;
use App\Models\HolidayTitle;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Traits\DataTable\WithSorting;
use App\Traits\Wordpress\WithWordpress;
use App\Repositories\ArticleRepository;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Homes extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithFileUploads, WithWordpress;

    public PartnerHome $editing;

    public $showPostModal = false;
    public $showHolidayModal = false;
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
        'editing.hero_id' => 'required',
        'editing.hero_type' => 'required',
        'editing.image' => 'sometimes',
        'editing.testimonial_url' => 'sometimes',
        'editing.conference_url' => 'sometimes',
        'editing.lang' => 'required',
    ];

    public function mount() {
        $this->editing = $this->makeBlankHero();
        $this->partners = Partner::get();
        $this->lang = App::currentLocale();
    }
    public function makeBlankHero()
    {
        return PartnerHome::make([]);
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' houses','type'=>'success']);
    }

    public function getRowsQueryProperty()
    {
        $query = PartnerHome::query();

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

        if ($this->editing->getKey()) $this->editing = $this->makeBlankHero();

        $this->showEditModal = true;
    }

    public function edit(PartnerHome $hero)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($hero)){
            $this->editing = $hero;
            if($this->editing->hero_type == 'holiday') {
                $this->holidaySearch = $this->editing->hero_id;
            } else {
                $this->postSearch = $this->editing->hero_id;
            }
        } 

        $this->showEditModal = true;
    }
    
    public function setAutoCompleteItem($type, $text, $id)
    {
        switch ($type) {
            case 'holidayId':
                $holiday = HolidayTitle::whereHolidayId($id)->whereLang($this->lang)->first();
                
                $this->editing->hero_id = $id;
                $this->holidaySearch = $holiday->holiday->id .'# '.$holiday->name;
                break;

            default:

                $wordpress  = new ArticleRepository();
                $post = $wordpress->getPostById($id);
                
                $this->editing->hero_id = $id;
                
                $this->postSearch = $post->postId .'# '.$post->title;
                break;
        }
    }

    public function save()
    {
        $name = null;
        $destinationPath = null;
        // $h = PartnerHome::whereHeroType($this->editing->hero_type)->whereLang($this->editing->lang)->first();
        // if($h) {
        //     return $this->notify(['message' => 'Hero already exist', 'type' => 'alert']);
        // }
        if ($this->upload) {

            $name = Str::random(30);

            $destinationPath = storage_path('app/partners') . '/' . $this->editing->partner_id;
            try {
                File::makeDirectory($destinationPath, 0777, false, false);
            } catch (Exception $e) {}

            $this->editing->image = $name . '.webp';
        }
        $this->validate();

        

        if ($this->upload && $name && $destinationPath) {
            $img = Image::make($this->upload->path());
            // Big Resize 2048
            if ($img->width() >= 2048) {
                $img->resize(2048, 2048, function ($contraint) {
                    $contraint->aspectRatio();
                })->encode('webp', 75)->save($destinationPath . '/large_' . $name . '.webp', 75);
            } else {
                $img->encode('webp', 75)->save($destinationPath . '/large_' . $name . '.webp', 75);
            }
            if ($img->width() >= 1024) {
                $img->resize(1024, 1024, function ($contraint) {
                    $contraint->aspectRatio();
                })->encode('webp', 75)->save($destinationPath . '/medium_' . $name . '.webp', 75);
            } else {
                $img->encode('webp', 75)->save($destinationPath . '/medium_' . $name . '.webp', 75);
            }
            if ($img->width() >= 400) {
                $img->encode('webp', 75)->resize(400, 400, function ($contraint) {
                    $contraint->aspectRatio();
                })->encode('webp', 75)->save($destinationPath . '/small_' . $name . '.webp', 75);
            } else {
                $img->encode('webp', 75)->save($destinationPath . '/small_' . $name . '.webp', 75);
            }
        }

        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'Hero well saved', 'type' => 'success']);
        //return redirect(request()->header('Referer'));
        //$this->holidayDocument->refresh();
    }

    public function render()
    {
        return view('livewire.admin.partner.homes', [
            'heros' => $this->rows,
        ])->layout('layouts.admin');
    }
}
