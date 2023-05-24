<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\HolidayDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayDescription>
 */
class HolidayDescriptionFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = HolidayDescription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'holiday_id' => Holiday::factory(),
            'description' => fake()->text(),
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
