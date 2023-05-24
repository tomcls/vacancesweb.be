<?php

namespace App\Data\Heremap;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class GeocodeNavigationPositionData extends Data
{
    public function __construct(
      #[MapInputName('Longitude')]
      public ?float  $longitude,
      #[MapInputName('Latitude')]
      public ?float $latitude,
    ) {}
}
