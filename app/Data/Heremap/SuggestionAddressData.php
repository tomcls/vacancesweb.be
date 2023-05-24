<?php

namespace App\Data\Heremap;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class SuggestionAddressData extends Data
{
    public function __construct(
      public string $country,
      public ?string $state,
      public ?string $county,
      public ?string $city,
      #[MapInputName('postalCode')]
      public ?string $postalCode,
      public ?string $street,
      public ?string $houseNumber,
    ) {}
}
