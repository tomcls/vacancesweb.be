<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\HouseTitle;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseTitle>
 */
class HouseTitleFactory extends Factory
{
   /**
     * @var string
     */
    protected $model = HouseTitle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->city().' House';
        $slug = Str::slug($name);
        return [
            'name' => fake()->city().' House',
            'house_id' => House::factory(),
            'name' => $name,
            'slug' => $slug,
            'lang' => fake()->randomElement(['fr', 'nl','en'])
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
