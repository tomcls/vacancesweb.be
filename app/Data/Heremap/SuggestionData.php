<?php
namespace App\Data\Heremap;

use App\Data\Heremap\SuggestionAddressData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\MapInputName;
# sail php artisan make:data HereData
class SuggestionData extends Data
{
    public function __construct(
        public SuggestionAddressData $address,
        public string $countryCode,
        public string $label,
        #[MapInputName('language')]
        public string $lang,
        public string $locationId,
        #[WithCast(LevelCast::class)]
        public Level $matchLevel
    ) {
    }
}