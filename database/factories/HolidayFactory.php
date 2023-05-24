<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\HolidayImage;
use App\Models\HolidayType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holiday>
 */
class HolidayFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Holiday::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::createFromTimeStamp(fake()->dateTimeBetween('-30 days', '+30 days')->getTimestamp());
        return [
            'user_id' => User::factory(),
            'holiday_type_id' => fake()->randomElement(HolidayType::pluck('id')),
            'longitude' => fake()->longitude(),
            'latitude' =>  fake()->latitude(),
            'startdate' =>  $startDate,
            'enddate' =>  Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addMonth(),
        ];
    }
}
