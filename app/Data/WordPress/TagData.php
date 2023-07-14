<?php

namespace App\Data\WordPress;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class TagData extends Data
{
  public function __construct(
    #[MapInputName('term_id')]
    public int $id,
    public string $name,
    public string $slug
  ) {}
}