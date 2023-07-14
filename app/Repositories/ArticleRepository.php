<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use App\Data\WordPress\PostData;
use App\Data\WordPress\CategoryData;
use GuzzleHttp\Psr7\Request as Psr7Request;
use App\Data\WordPress\ArticleSuggestionData;
use App\Data\WordPress\ReportageSuggestionData;

class ArticleRepository
{

    public $url = 'https://blog.vacancesweb.be';
    public $version = "/wp-json/vw/v2/";
    public $total = 0;
    /**
     * lang
     * search
     * 
     * 
     */
    public  function suggest(array $filters): array
    {
        $lang = 2;
        switch ($filters['lang']) {
            case 'fr':
                $lang = 2;
                break;
            case 'nl':
                $lang = 1;
                break;
            case 'en': // no post in english
                $lang = 2;
                break;
            default:
                # code...
                break;
        }
        $uri = 'search_articles';
        if (isset($filters['type']) && $filters['type'] == 'reportage') {
            $uri = 'search_reports';
        }
        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?search=' . $filters['search'] . '&language_id=' . $lang;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $suggestions = [];
        foreach ($result as $suggestion) {
            if (isset($filters['type']) && $filters['type'] == 'reportage') {
                $s = ReportageSuggestionData::from($suggestion);
            } else {
                $s = ArticleSuggestionData::from($suggestion);
            }
            $suggestions[] = $s;
        }
        return $suggestions;
    }

    /**
     * lang
     * search
     * 
     * 
     */
    public  function getPostById($id): PostData
    {

        $uri = 'post';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?id=' . $id;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        return PostData::from($result) ?? null;
    }

    /**
     * lang
     * search
     * 
     * 
     */
    public  function getPostsByIds($ids): Array
    {

        $uri = 'posts';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?ids=' . implode(',',$ids);
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $posts = [];
        foreach ($result as $post) {
            array_push($posts, PostData::from($post));
        }
        return $posts ?? null;
    }

    /**
     * $slug string
     */
    public  function getReportageBySlug($slug): PostData
    {

        $uri = 'reportage';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?slug=' . $slug;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        return PostData::from($result) ?? null;
    }
    /**
     * $slug string
     */
    public  function getArticleBySlug($slug): PostData
    {

        $uri = 'article';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?slug=' . $slug;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        return PostData::from($result) ?? null;
    }
    /**
     * 
     */
    public  function getArticlesByThema($slug, $lang = null, $page = null, $perPage = null)
    {
        $lang = $lang ?? 'fr';
        $p = $page ? '&page=' . $page : '';;
        $pp = $perPage ? '&per_page=' . $perPage : '&per_page=10';

        $uri = 'thema';

        $client = new Client();
        $url = $this->url  . $this->version . $uri .'?thema='.$slug. '&lang=' . $lang . $p . $pp;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $articles = [];
        $this->total = $result['total'];
        foreach ($result['result'] as $article) {
            array_push($articles, PostData::from($article));
        }
        return $articles;
    }
    /**
     * 
     */
    public  function getArticles($lang = null, $page = null, $perPage = null)
    {
        $lang = $lang ?? 'fr';
        $p = $page ? '&page=' . $page : '';;
        $pp = $perPage ? '&post_per_page=' . $perPage : '&post_per_page=30';

        $uri = 'articles';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?lang=' . $lang . $p . $pp;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $articles = [];
        $this->total = $result['total'];
        foreach ($result['result'] as $article) {
            array_push($articles, PostData::from($article));
        }
        return $articles;
    }
    /**
     * 
     */
    public  function getReportages($lang = null, $page = null, $perPage = null)
    {
        $lang = $lang ?? 'fr';
        $p = $page ? '&page=' . $page : '';;
        $pp = $perPage ? '&post_per_page=' . $perPage : '&post_per_page=30';

        $uri = 'reportages';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?lang=' . $lang . $p . $pp;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $articles = [];
        $this->total = $result['total'];
        foreach ($result['result'] as $article) {
            array_push($articles, PostData::from($article));
        }
        return $articles;
    }
    /**
     * 
     */
    public function getCategories($lang = 'fr')
    {

        $client = new Client();
        $uri = 'categories';
        $url = $this->url  . $this->version . $uri . '?lang=' . $lang;
        $request = new Psr7Request('GET', $url);

        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });

        $result = $promise->wait();

        $categories = [];

        foreach ($result as $category) {
            array_push($categories, CategoryData::from($category));
        }

        return $categories;
    }
    /**
     * 
     */
    public  function getChilds($postId)
    {
        $uri = 'childs';

        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?id=' . $postId;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $childs = [];
        foreach ($result as $child) {
            array_push($childs, PostData::from($child));
        }
        return $childs;
    }
    /**
     * 
     */
    public  function getRelated($categoryIds, $lang = 'fr')
    {

        $uri = 'related';
        $categories = $this->removeCategoryIds($categoryIds);
        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?catid=' . $categories[0] . '&total_post=6&lang=' . $lang;
        logger($url);
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        $related = [];
        foreach ($result['result'] as $r) {
            array_push($related, PostData::from($r));
        }
        return $related;
    }

    public function removeCategoryIds($ids)
    {
        foreach ([2618, 2620, 21, 23, 17, 19, 9, 11, 27, 25, 1, 7, 2920] as  $id) {
            # code...
            $index = array_search($id, $ids);
            if ($index !== false) {
                unset($ids[$index]);  // $arr = ['b', 'c']
            }
        }
        return $ids;
    }
}
