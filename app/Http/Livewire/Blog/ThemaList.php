<?php

namespace App\Http\Livewire\Blog;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use App\Repositories\ArticleRepository;

class ThemaList extends Component
{
    public $categories = [];
    private ArticleRepository $articleRepository;
    public $lang = 'fr';
    public function mount() {
        $this->lang = App::currentLocale();
    }
    public function getRowsProperty()
    {
        $this->articleRepository = new ArticleRepository();
        return $this->articleRepository->getCategories($this->lang);
    }
    public function render()
    {
        return view('livewire.blog.thema-list', [
            'rows' => $this->rows,
        ]);
    }
}
