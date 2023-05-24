<?php

namespace App\Data\Heremap;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class GeocodeData extends Data
{
  public function __construct(
    #[MapInputName('MatchLevel')]
    public string  $matchLevel,
    #[MapInputName('MatchQuality')]
    public GeocodeQualityData $matchQuality,
    #[MapInputName('MatchType')]
    public ?string $matchType,
    #[MapInputName('Location')]
    public GeocodeLocationData $location,
  ) {}
}
