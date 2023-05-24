<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\AmenityTranslation;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amenities = json_decode(file_get_contents("./database/data/amenities.json"));
        $amenityIds = [];
       
        foreach ($amenities as $a) {
            $amenity = new Amenity();
            $amenity->code = Str::slug($a->attribute_des);
            $amenity->category = Str::slug($a->category_des);
            try{
                if(!in_array($a->attribute_id,$amenityIds) && $a->language_id == 3) {
                    $amenity->save();
                    array_push($amenityIds,$a->attribute_id);
                }
            } catch (Exception $e) {
                logger($e->getMessage());
            }
            foreach ($amenities as $trans) {
                if (in_array($trans->language_id, [1, 2, 3]) && $a->attribute_id == $trans->attribute_id) {
                    $translation = new AmenityTranslation();
                    $translation->amenity_id = $amenity->id;
                    $translation->name = $trans->attribute_des;
                    $translation->lang = $trans->language_id == 3 ? 'en' : ($trans->language_id == 2 ? 'fr' : 'nl');
                    try{
                        $translation->save();
                    } catch (Exception $e) {}
                }
            }
        }
    }
}

/**
 * 
 * select ad.attribute_id, 
ad.attribute_des_id, 
ad.attribute_des, 
ad.language_id, 
a.attribute_category_id, 
(select t_attribute_category_des.attribute_category_des from t_attribute_category_des where t_attribute_category_des.attribute_category_id = ac.attribute_category_id and t_attribute_category_des.language_id =ad.language_id ) as category_des
from t_attribute_des ad

left join t_attribute a on a.attribute_id = ad.attribute_id
left join t_attribute_category ac on ac.attribute_category_id = a.attribute_category_id
where ad.language_id in (1,2,3);
 */