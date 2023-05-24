<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\HolidayTitle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayTitle>
 */
class HolidayTitleFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = HolidayTitle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->country().' Hotel';
        $slug = Str::slug($name);
        return [
            'name' => fake()->country().' Hotel',
            'holiday_id' => Holiday::factory(),
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
