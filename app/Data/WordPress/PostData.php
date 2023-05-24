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
    #[MapInputName('id')]
    public string $postId,
  ) {}
}