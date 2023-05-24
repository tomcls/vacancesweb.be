<?php

namespace App\Data\Heremap;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class GeocodeAddressData extends Data
{
    
  public function __construct(
    #[MapInputName('Country')]
    public string  $country,
    #[MapInputName('State')]
    public ?string $state,
    #[MapInputName('County')]
    public ?string $county,
    #[MapInputName('City')]
    public ?string $city,
    #[MapInputName('PostalCode')]
    public ?string $postalCode,
    #[MapInputName('District')]
    public ?string $district,
    #[MapInputName('Street')]
    public ?string $street,
    #[MapInputName('HouseNumber')]
    public ?string $houseNumber,
    #[MapInputName('Label')]
    public ?string $label,
    #[MapInputName('AdditionalData')]
    public ?array $additionalData,
  ) {}
}
