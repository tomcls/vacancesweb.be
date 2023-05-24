<?php
namespace App\Data\Heremap;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class LevelCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): Level
    {
        return Level::from($value);
    }
}