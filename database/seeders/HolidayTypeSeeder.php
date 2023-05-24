<?php

namespace Database\Seeders;

use App\Models\HolidayType;
use App\Models\HolidayTypeTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ht = new HolidayType();
        $ht->code = 'citytrip';
        $ht->save();
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'fr';
        $htt->slug = 'citytrip';
        $htt->name = 'Séjour';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'nl';
        $htt->slug = 'verblijf';
        $htt->name = 'Verblijf';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'en';
        $htt->slug = 'stay';
        $htt->name = 'Stay';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);

        $ht = new HolidayType();
        $ht->code = 'club';
        $ht->save();
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'fr';
        $htt->slug = 'club';
        $htt->name = 'Club';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'nl';
        $htt->slug = 'club';
        $htt->name = 'Club';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'en';
        $htt->slug = 'club';
        $htt->name = 'Club';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);

        $ht = new HolidayType();
        $ht->code = 'circuit';
        $ht->save();
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'fr';
        $htt->slug = 'circuit';
        $htt->name = 'Circuit';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'nl';
        $htt->slug = 'circuit';
        $htt->name = 'Circuit';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'en';
        $htt->slug = 'circuit';
        $htt->name = 'Circuit';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        
        $ht = new HolidayType();
        $ht->code = 'cruise';
        $ht->save();
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'fr';
        $htt->slug = 'croisiere';
        $htt->name = 'Croisière';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'nl';
        $htt->slug = 'cruise';
        $htt->name = 'Cruise';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
        $htt = new HolidayTypeTranslation();
        $htt->lang = 'en';
        $htt->slug = 'cruise';
        $htt->name = 'Cruise';
        $htt->holiday_type_id = $ht->id;
        $ht->holidayTypeTranslations()->save($htt);
    }
}
