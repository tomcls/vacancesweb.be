<?php

namespace Database\Factories;

use App\Models\Amenity;
use App\Models\House;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseAmenity>
 */
class HouseAmenityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'house_id' => House::factory(),
            'amenity_id' => Amenity::factory(),
            'value' => fake()->randomElement([null,1,2,3,4,5,6,7,8])
        ];
    }
}
