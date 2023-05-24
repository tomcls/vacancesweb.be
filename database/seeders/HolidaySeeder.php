<?php

namespace Database\Seeders;

use App\Models\Holiday;
use App\Models\HolidayTitle;
use Database\Factories\HolidayTitleFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $holidays = Holiday::factory()->count(3)->create();;
        HolidayTitle::factory()
            ->count(3)
            ->for($holidays)
            ->create();
    }
}
