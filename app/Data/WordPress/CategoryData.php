<?php

namespace App\Data\WordPress;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class CategoryData extends Data
{
  public function __construct(
    #[MapInputName('cat_ID')]
    public int $id,
    public string $name,
    public string $slug,
    #[MapInputName('category_parent')]
    public int $parentCategoryId,
    #[MapInputName('category_count')]
    public int $total
  ) {}
}