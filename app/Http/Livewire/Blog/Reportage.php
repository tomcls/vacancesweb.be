<?php

namespace App\Http\Livewire\Blog;


use Livewire\Component;
use App\Data\WordPress\PostData;
use Illuminate\Support\Facades\App;
use App\Repositories\ArticleRepository;

class Reportage extends Component
{
    public $lang = 'fr';
    private ArticleRepository $articleRepository;
    public $slug = null;
    public $content;
    public $tagSlugs = [];
    public $childs = [];
    public $related = [];
    private PostData $reportage;

    public function mount($slug)
    {
        $this->lang = App::currentLocale();
        $this->slug = $slug;

        $this->articleRepository = new ArticleRepository();

        $this->reportage = $this->articleRepository->getReportageBySlug($this->slug);
        $this->childs = $this->articleRepository->getChilds($this->reportage->postId);
        $this->related = $this->articleRepository->getRelated($this->reportage->categories, $this->lang);
        if ($this->reportage->tags) {
            foreach ($this->reportage->tags as $key => $tag) {
                if ($tag['slug'] != 'francais') {
                    $tag = str_replace('-fr', '', $tag['slug']);
                    $tag = str_replace('-nl', '', $tag);
                    $this->tagSlugs[] = $tag;
                }
            }
        }
        $content = $this->reportage->htmlStrip;
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        if ($content) {

            $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            libxml_use_internal_errors(false);
            $xpath = new \DomXPath($doc);

            $galleryImages = $xpath->query('//figure[contains(@class, "relative")]');
            foreach ($galleryImages as $key => $image) {
                $value = $galleryImages->item($key)->nodeValue;
                /*  $src = $image->getAttribute('src');
                $image->setAttribute('src', 'https://www.vacancesweb.be/assets/img/placeholder700.png');
                $image->setAttribute('srcset', '');
                $image->setAttribute('data-src', $src);*/
            }

            /* $img = $xpath->query("//img");
            foreach ($img as $key => $n) {
                $n->setAttribute('class', 'rounded-b-md my-2');
            }*/

            $h1 = $xpath->query("//h1");
            foreach ($h1 as $key => $n) {
                $n->setAttribute('class', 'text-slate-600 my-5 font-bold text-xl');
            }

            $h2 = $xpath->query("//h2");
            foreach ($h2 as $key => $n) {
                $n->setAttribute('class', 'text-slate-600 my-5 font-bold text-xl');
            }

            $h3 = $xpath->query("//h3");
            foreach ($h3 as $key => $n) {
                $n->setAttribute('class', 'text-slate-600 my-5 font-bold text-xl');
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
                $n->setAttribute('class', ' rounded-md absolute bottom-0 py-2 pl-2 w-full bg-black opacity-80 text-white ');
            }
            /*$nodeList = $xpath->query('//*[contains(@class, "rounded-b-md my-2")]');
            foreach ($nodeList as $key => $node) {
                $value = $nodeList->item($key)->nodeValue;
                $src = $node->getAttribute('src');
                $node->setAttribute('src', 'https://www.vacancesweb.be/assets/img/placeholder700.png');
                $node->setAttribute('srcset', '');
                $node->setAttribute('data-src', $src);
            }*/

            $content = $doc->saveHTML();
            $content = preg_replace('/\s*\|\s*((?:(?!<\/span>\s*<\/figcaption>).)*)<\/span>\s*<\/figcaption>/i', ' &copy;</span></figcaption>', $content);
            $content = str_replace('<span>cms </span>', '', $content);
            $this->content = $content; //$doc->saveHTML();
        }
    }
    public function render()
    {
        return view('livewire.blog.reportage', ['reportage' => $this->reportage])->layout('layouts.blog');
    }
}
