<?php

namespace App\Data\Heremap;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class GeocodeLocationData extends Data
{
  public function __construct(
    #[MapInputName('LocationId')]
    public string  $locationId,
    #[MapInputName('LocationType')]
    public string $locationType,
    #[MapInputName('DisplayPosition')]
    public GeocodeNavigationPositionData $displayPosition,
    #[MapInputName('NavigationPosition')]
    public ?Array $navigationPosition,
    #[MapInputName('Address')]
    public GeocodeAddressData $address,
    #[MapInputName('Shape')]
    public ?array $shape
  ) {}
}
