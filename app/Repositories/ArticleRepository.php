<?php

namespace App\Repositories;

use App\Data\WordPress\PostData;
use App\Data\WordPress\ArticleSuggestionData;
use App\Data\WordPress\ReportageSuggestionData;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;

class ArticleRepository
{

    public $url = 'https://blog.vacancesweb.be';
    public $version = "/wp-json/vw/v2/";

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
       
        $uri = 'get_posts_by_id';
        
        $client = new Client();
        $url = $this->url  . $this->version . $uri . '?id=' . $id ;
        $request = new Psr7Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            return json_decode($response->getBody(), 1);
        });
        $result = $promise->wait();
        return PostData::from($result[0])??null;
    }
}
