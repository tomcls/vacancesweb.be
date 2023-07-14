<?php

namespace App\Data\WordPress;

use Faker\Core\Number;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class PostData extends Data
{
  public function __construct(
    public string $image,
    public string $cover,
    #[MapInputName('cover_mobile')]
    public string $coverMobile,
    public string $subtitle,
    public array $categories,
    public string $title,
    public string $slug,
    #[MapInputName('date_modified')]
    public ?string $dateUpdated,
    #[MapInputName('id')]
    public string $postId,
    public ?array $tags,
    public ?string $lang,
    public ?array $languages,
    public ?string $content,
    public $html,
    #[MapInputName('html_strip')]
    public ?string $htmlStrip,
    public ?array $gallery,
    public ?array $author,
  ) {
  }
}
