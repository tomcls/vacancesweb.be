<?php

namespace App\Data\WordPress;

use Faker\Core\Number;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class ArticleSuggestionData extends Data
{
  public function __construct(
    public string $image,
    public ?string $subtitle,
    public array $categories,
    public string $title,
    public string $slug,
    #[MapInputName('article_id')]
    public string $articleId,
  ) {}
}