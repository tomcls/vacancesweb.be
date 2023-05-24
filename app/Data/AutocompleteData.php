<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AutocompleteData extends Data
{
    public function __construct(
      public ?string $id,
      public string $title,
      public string $subtitle,
      public ?string $image,
    ) {}
}
