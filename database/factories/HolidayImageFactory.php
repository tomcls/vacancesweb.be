<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\HolidayImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HolidayImage>
 */
class HolidayImageFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = HolidayImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'name' => 'test.jpg',
            'holiday_id' => Holiday::factory(),
            'origin' => 'https://images.unsplash.com/photo-1614547150983-e574bc307e1a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80',
            'sort'=> fake()->randomElement([1,2,3,4,5,6,7,8])
        ];
    }
}
