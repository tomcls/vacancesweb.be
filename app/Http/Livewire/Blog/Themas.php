<?php

namespace App\Http\Livewire\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Repositories\ArticleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Themas extends Component
{
    public $lang;
    public $page = 1;
    public $perPage = 12;
    public $currentPage = 1;
    private ArticleRepository $articleRepository;
    public LengthAwarePaginator $pagination;
    protected $queryString = ['page'];
    public $slug;


    public function mount($slug,Request $request)
    {
        $this->lang = App::currentLocale();
        $this->page = $request['page'] ?? 1;
        $this->slug = $slug;
    }

    public function gotoPage($pageNumber) {
        $this->page = $pageNumber;
    }

    public function getRowsProperty():LengthAwarePaginator
    {
        $this->articleRepository = new ArticleRepository();
        $articles = $this->articleRepository->getArticlesByThema($this->slug, $this->lang, $this->page, $this->perPage);
        $pagination = new LengthAwarePaginator(
            $articles,
            $this->articleRepository->total,
            $this->perPage,
            $this->page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
        return $pagination;
    }

    public function render()
    {
        return view('livewire.blog.themas', [
            'rows' => $this->rows,
        ])->layout('layouts.blog');
    }
}
