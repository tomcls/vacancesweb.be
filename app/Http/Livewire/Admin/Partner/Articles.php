<?php

namespace App\Http\Livewire\Admin\Partner;

use App\Models\Partner;
use App\Models\PartnerArticle;
use App\Repositories\ArticleRepository;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithSorting;
use App\Traits\Wordpress\WithWordpress;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Articles extends Component
{
    use WithSorting, WithWordpress, WithCachedRows;

    public PartnerArticle $editing;

    public $showDeleteModal = false;
    public $showEditModal = false;

    public $partners;
    protected $queryString = ['sorts'];

    public $posts = [];
    public $postSearch = null;
    public $postId = null;

    public $showPostModal = false;

    public $lang;

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem', 'reorder' => 'reorder'];

    public $showFilters = false;

    public $rules = [
        'editing.partner_id' => 'required',
        'editing.post_id' => 'required',
        'editing.lang' => 'required',
        'editing.sort' => 'sometimes',
    ];
    public $filters = [
        "id" => null,
        "partner_id" => null,
        "post_id" => null,
        'lang' => null,
    ];

    public function mount()
    {
        $this->editing = $this->makeBlankHoliday();
        $this->partners = Partner::get();
        $this->lang = App::currentLocale();
    }
    public function makeBlankHoliday()
    {
        return PartnerArticle::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' holiday items', 'type' => 'success']);
    }

    public function getRowsQueryProperty()
    {
        $query = PartnerArticle::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->when($this->filters['lang'], fn ($query, $lang) => $query->where('lang', '=', $lang))
            ->when($this->filters['partner_id'], fn ($query, $id) => $query->where('partner_id', '=', $id))
            ->when($this->filters['post_id'], fn ($query, $id) => $query->where('post_id', '=', $id));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->get();
    }
    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankHoliday();

        $this->showEditModal = true;
    }

    public function edit(PartnerArticle $item)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($item)) {
            $this->editing = $item;
            $this->postSearch = $this->editing->post_id;
        }
        $this->showEditModal = true;
    }

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

    public function setAutoCompleteItem($type, $text, $id)
    {
        $wordpress  = new ArticleRepository();
        $post = $wordpress->getPostById($id);
        // $object = [
        //     'id' => $post->postId,
        //     'image' => $post->cover,
        //     'title' => $post->title,
        //     'url' => '/article/' . $post->slug,
        //     'author' => $post->author
        // ];
        // //  $this->homepage[$this->lang][$this->heroItem] = $object;
        $this->showPostModal = false;
        $this->postSearch = $post->postId . '# ' . $post->title;

        $this->editing->post_id = $id;
        $this->posts = [];
    }
    public function reorder($orderedIds)
    {
        collect($orderedIds)->map(function ($id, $key) {
            return PartnerArticle::whereId($id)->update(['sort' => $key]);
        });
    }
    public function save()
    {
        $h = PartnerArticle::whereLang($this->editing->lang)->wherePostId($this->editing->post_id)->wherePartnerId($this->editing->partner_id)->first();
        if ($h && $h->id) {
            return $this->notify(['message' => 'holiday item already exist', 'type' => 'alert']);
        }
        $this->validate();
        $total = PartnerArticle::wherePartnerId($this->editing->partner_id)->whereLang($this->editing->lang)->count();
        $this->editing->sort = $total;
        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'holiday item well saved', 'type' => 'success']);
        $this->emit('initDragAndDrop');
    }
    public function dehydrateRows() {
        logger("dehydrateRows");
    }
    public function updatedRows() {
        logger("updatedRows");
    }
    public function render()
    {
        logger("render");

        return view('livewire.admin.partner.articles', [
            'rows' => $this->rows,
        ])->layout('layouts.admin');
    }
}
