<?php

namespace App\Http\Livewire\Blog;

use Livewire\Component;
use App\Data\WordPress\PostData;
use Illuminate\Support\Facades\App;
use App\Repositories\ArticleRepository;

class Article extends Component
{
    public $lang = 'fr';
    private ArticleRepository $articleRepository;
    public $slug = null;
    public $content;
    public $tagSlugs = [];
    public $related = [];
    private PostData $article;

    public function mount($slug)
    {
        $this->lang = App::currentLocale();
        $this->slug = $slug;

        $this->articleRepository = new ArticleRepository();
        

        $this->article = $this->articleRepository->getArticleBySlug($this->slug);        
        $this->related = $this->articleRepository->getRelated($this->article->categories, $this->lang);



        if($this->article->tags) {
            foreach ($this->article->tags as $key => $tag) {
                
                if ($tag['slug'] != 'francais') {
                    $tag = str_replace('-fr', '', $tag['slug']);
                    $tag = str_replace('-nl', '', $tag);
                    $this->tagSlugs[] =$tag;
                }
            }
        }
        
        $content = $this->article->html;
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_use_internal_errors(false);
        $xpath = new \DomXPath($doc);
        $img = $xpath->query("//img");
        foreach ($img as $key => $n) {
            $n->setAttribute('class', 'rounded-md my-2');
        }
        $h2 = $xpath->query("//h2");

        foreach ($h2 as $key => $n) {
            $n->setAttribute('class', 'text-slate-600 my-5 font-bold text-xl');
        }
        $h3 = $xpath->query("//h3");
        foreach ($h3 as $key => $n) {
            $n->setAttribute('class', 'text-slate-600 my-5 font-bold text-xl');
        }

        $link = $xpath->query('//*[contains(@class, "pink-gradient")]');
        foreach ($link as $key => $n) {
            $n->setAttribute('class', 'bg-blue-600 py-2 px-3 text-white rounded hover:bg-blue-800 my-5 font-normal inline-block');
        }

        $figure = $xpath->query("//figure");
        foreach ($figure as $key => $n) {
            $n->setAttribute('class', 'relative');
        }
        $mediaCredit = $xpath->query("//span[@class='media-credit']");
        foreach ($mediaCredit as $key => $node) {
            $value = $mediaCredit->item($key)->nodeValue;
            $span = $doc->createElement('span', $value);
            $mediaCredit->item($key)->parentNode->replaceChild($span, $mediaCredit->item($key));
        }
        $figCaption = $xpath->query("//figcaption");
        foreach ($figCaption as $key => $n) {
            $n->setAttribute('class', ' rounded-b-md absolute bottom-0 py-2 pl-2 w-full bg-black opacity-80 text-white ');
        }
        $nodeList = $xpath->query('//*[contains(@class, "rounded-md my-2")]');
        foreach ($nodeList as $key => $node) {
            $value = $nodeList->item($key)->nodeValue;
            $src = $node->getAttribute('src');
            $node->setAttribute('src', 'https://www.vacancesweb.be/assets/img/placeholder700.png');
            $node->setAttribute('srcset', '');
            $node->setAttribute('data-src', $src);
            
        }
        $content = $doc->saveHTML();
        $content = preg_replace('/\s*\|\s*((?:(?!<\/span>\s*<\/figcaption>).)*)<\/span>\s*<\/figcaption>/i', ' &copy;</span></figcaption>', $content);
        $content = str_replace('<span>cms </span>', '', $content);
        $this->content = $content; //$doc->saveHTML();
    }

    public function render()
    {
        return view('livewire.blog.article', ['article' => $this->article])->layout('layouts.blog');
    }
}
