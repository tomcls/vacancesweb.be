<?php

namespace App\Http\Livewire\Blog;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Repositories\ArticleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Reportages extends Component
{
    public $lang;
    public $page = 1;
    public $perPage = 30;
    public $currentPage = 1;
    private ArticleRepository $articleRepository;
    public LengthAwarePaginator $pagination;
    protected $queryString = ['page'];


    public function mount(Request $request)
    {
        $this->lang = App::currentLocale();
        $this->page = $request['page'] ?? 1;
    }

    public function gotoPage($pageNumber) {
        $this->page = $pageNumber;
    }

    public function getRowsProperty():LengthAwarePaginator
    {
        $this->articleRepository = new ArticleRepository();
        $reportages = $this->articleRepository->getReportages($this->lang, $this->page, $this->perPage);
        $pagination = new LengthAwarePaginator(
            $reportages,
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
        return view('livewire.blog.reportages', [
            'rows' => $this->rows,
        ])->layout('layouts.blog');
    }
}
