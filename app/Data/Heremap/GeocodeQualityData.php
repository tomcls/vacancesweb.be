<?php

namespace App\Data\Heremap;

use Faker\Core\Number;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;

class GeocodeQualityData extends Data
{
  public function __construct(
    #[MapInputName('Country')]
    public ?float  $country,
    #[MapInputName('State')]
    public ?float $state,
    #[MapInputName('County')]
    public ?float $county,
    #[MapInputName('City')]
    public ?float $city,
    #[MapInputName('PostalCode')]
    public ?float $postalCode,
    #[MapInputName('District')]
    public ?float $district,
    #[MapInputName('Street')]
    public ?Array $street,
    #[MapInputName('HouseNumber')]
    public ?float $houseNumber,
  ) {}
}
