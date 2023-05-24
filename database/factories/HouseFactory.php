<?php

namespace Database\Factories;

use App\Models\HouseType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nr_of_persons_min = fake()->numberBetween(2, 10);
        return [
            'user_id' => User::factory(),
            'house_type_id' => fake()->randomElement(HouseType::pluck('id')),
            'longitude' => fake()->longitude(),
            'latitude' =>  fake()->latitude(),
            'street' =>  fake()->streetAddress(),
            'street_number' =>  fake()->buildingNumber(),
            'street_box' =>  fake()->buildingNumber(),
            'zip' =>  fake()->postcode(),
            'city' =>  fake()->city(),
            'phone' =>  fake()->phoneNumber(),
            'email' =>  fake()->email(),
            'acreage' =>  fake()->numberBetween(50, 450),
            'longitude' =>  fake()->longitude(),
            'latitude' =>  fake()->latitude(),
            'number_people' =>  fake()->numberBetween(2, 10),
            'movie_url' =>  fake()->url(),
            'web_url' =>  fake()->url(),
            'active' =>  fake()->numberBetween(0, 1),
        ];
    }
}
