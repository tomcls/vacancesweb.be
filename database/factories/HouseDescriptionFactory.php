<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\HouseDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseDescription>
 */
class HouseDescriptionFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = HouseDescription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'house_id' => House::factory(),
            'description' => fake()->text(),
            'environment' => fake()->text(),
            'lang' => fake()->randomElement(['fr', 'en','nl'])
        ];
    }
    public function fr() {
        return $this->state(['lang'=>'fr']);
    }
    public function nl() {
        return $this->state(['lang'=>'nl']);
    }
    public function en() {
        return $this->state(['lang'=>'en']);
    }
}
