<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\HolidayPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayPrice>
 */
class HolidayPriceFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = HolidayPrice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'holiday_id' => Holiday::factory(),
            'departure_date' => Carbon::createFromTimeStamp(fake()->dateTimeBetween('0 days', '+90 days')->getTimestamp()),
            'departure_from' => 'bruxelles',
            'duration_days' => fake()->randomElement([4,6,9,11,12,13]),
            'duration_nights' => fake()->randomElement([5,7,10,12,13,14]),
            'price_customer' => fake()->randomElement([5,7,10,12,13,14]),
            'price' => fake()->randomElement([5,7,10,12,13,14]),
            'discount' => fake()->randomElement([5,7,10,12,13,14]),
            'lowest_price' => fake()->randomElement([5,7,10,12,13,14]),
            'info' => fake()->randomElement(['sp','ti','bb','ls','dp','pcbni','nc']),
        ];
    }
}
