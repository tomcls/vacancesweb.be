<?php
namespace App\Traits\Wordpress;

use App\Data\AutocompleteData;

trait WithWordpress
{
    public function formatArticleSuggestions($suggestions)
    {
        $result = [];
        foreach ($suggestions as  $post) {
            $autocomplete = AutocompleteData::from([
                'id' => $post->articleId,
                'title' => $post->title,
                'subtitle' => ' ',
                'image' => $post->image,
            ]);
            $result[] = $autocomplete;
        }
        return $result;
    }

    public function formatReportageSuggestions($suggestions)
    {
        $result = [];
        foreach ($suggestions as  $post) {
            $autocomplete = AutocompleteData::from([
                'id' => $post->reportageId,
                'title' => $post->title,
                'subtitle' => $post->subtitle,
                'image' => $post->image,
            ]);
            $result[] = $autocomplete;
        }
        return $result;
    }
}
