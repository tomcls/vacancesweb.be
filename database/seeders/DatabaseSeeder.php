<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Amenity;
use App\Models\Cost;
use App\Models\Holiday;
use App\Models\HolidayDescription;
use App\Models\HolidayPrice;
use App\Models\HolidayTitle;
use App\Models\House;
use App\Models\HouseDescription;
use App\Models\HouseHighlight;
use App\Models\HousePublication;
use App\Models\HouseTitle;
use App\Models\Invoice;
use App\Models\InvoiceTransaction;
use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //sail artisan db:seed --class=HolidayTypeSeeder
        $this->call([
            HolidayTypeSeeder::class,
        ]);
        $holidays = Holiday::factory()
            ->hasHolidayImages(10)
            ->has(HolidayPrice::factory()->count(5))
            ->has(HolidayTitle::factory()->fr())
            ->has(HolidayTitle::factory()->nl())
            ->has(HolidayTitle::factory()->en())
            ->has(HolidayDescription::factory()->fr())
            ->has(HolidayDescription::factory()->nl())
            ->has(HolidayDescription::factory()->en())
            ->count(3)
            ->create();

        $this->call([
            HouseTypeSeeder::class,
        ]);
        $this->call([
            AmenitySeeder::class,
        ]);
        $this->call([
            CostSeeder::class,
        ]);
        $houses = House::factory()
            ->hasHouseImages(10)
            ->has(HouseTitle::factory()->fr())
            ->has(HouseTitle::factory()->nl())
            ->has(HouseTitle::factory()->en())
            ->has(HouseDescription::factory()->fr())
            ->has(HouseDescription::factory()->nl())
            ->has(HouseDescription::factory()->en())
            ->count(5)
            ->create();

        $amenities = Amenity::whereCategory('comfort')->get();
        $costs = Cost::get();
        $houses->each(function ($house) use ($amenities, $costs) {
            $house->amenities()->attach($amenities->random(10), ['value' => fake()->randomElement([null, 5, 7, null, null, 13, 14])]);
            $house->costs()->attach($costs->random(10), ['price' => fake()->randomElement([10, 25, 100, 15])]);
            $startdate = Carbon::createFromTimeStamp(fake()->dateTimeBetween('-400 days', '0 days')->getTimestamp());
            $housePublication =  HousePublication::create(
                [
                    'house_id' => $house->id,
                    'startdate' =>  $startdate,
                    'enddate' => Carbon::createFromFormat('Y-m-d H:i:s', $startdate)->addYear()
                ]
            );
            $invoice =  Invoice::create(
                [
                    'user_id' => $house->user_id,
                    'invoice_num' =>  Str::random(20),
                    'payment_status' =>  'success',
                    'date_payed' =>  $startdate,
                ]
            );
            InvoiceTransaction::create(
                [
                    'invoice_id' => $invoice->id,
                    'price' =>  149.00,
                    'reference' =>  $housePublication->id,
                    'type' =>  'house_publication'
                ]
            );
            $startdate = Carbon::createFromTimeStamp(fake()->dateTimeBetween('-400 days', '0 days')->getTimestamp());
            HouseHighlight::create([
                'house_id' => $house->id,
                'startdate' => $startdate,
                'enddate' => Carbon::createFromFormat('Y-m-d H:i:s', $startdate)->addMonth(),
            ]);
            $startdate = Carbon::createFromTimeStamp(fake()->dateTimeBetween('-20 days', '0 days')->getTimestamp());
            HouseHighlight::create([
                'house_id' => $house->id,
                'startdate' => $startdate,
                'enddate' => Carbon::createFromFormat('Y-m-d H:i:s', $startdate)->addMonth(),
            ]);
        });
        Partner::insert([
            ['code' => 'lesoir'],
            ['code' => 'soirmag'],
            ['code' => 'sudinfo'],
            ['code' => '7dimanche'],
            ['code' => 'cinevoyages'],
            ['code' => 'cineweekend'],
            ['code' => 'grande'],
            ['code' => 'vlan'],
        ]);

    }
}
