<?php

namespace Database\Seeders;

use App\Models\HouseType;
use App\Models\HouseTypeTranslation;
use Exception;
use Illuminate\Database\Seeder;

class HouseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = json_decode(file_get_contents("./database/data/t_house_type_des.json"));
        $typeIds = [];
        foreach ($types as $type) {
            $houseType = new HouseType();
            $houseType->code = $type->slug;
            try{
                if(!in_array($type->house_type_id,$typeIds) && $type->language_id == 3) {
                    $houseType->save();
                   
                    array_push($typeIds,$type->house_type_id);
                }
            } catch (Exception $e) {
                logger($e->getMessage());
            }
            foreach ($types as $trans) {
                if (in_array($trans->language_id, [1, 2, 3]) && $type->house_type_id == $trans->house_type_id) {
                    $translation = new HouseTypeTranslation();
                    $translation->house_type_id = $houseType->id;
                    $translation->name = $trans->house_type_des;
                    $translation->slug = $trans->slug;
                    $translation->lang = $trans->language_id == 3 ? 'en' : ($trans->language_id == 2 ? 'fr' : 'nl');
                    try{
                        $translation->save();
                    } catch (Exception $e) {}
                }
            }
        }
    }
}
