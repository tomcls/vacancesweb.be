<?php

namespace Database\Seeders;

use App\Models\Cost;
use App\Models\CostTranslation;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $costs = json_decode(file_get_contents("./database/data/t_cost.json"));
        $costIds = [];
       
        foreach ($costs as $a) {
            $cost = new Cost();
            $cost->code = Str::slug($a->cost_des);
            $cost->pay_available = $a->pay_available;
            $cost->mandatory = $a->fl_mandatory;
            $cost->pay_to_owner = $a->fl_pay_to_owner;
            $cost->cost_unit = $a->cost_unit;
            $cost->order = $a->sortorder;
            try{
                if(!in_array($a->cost_id,$costIds) && $a->language_id == 3) {
                    $cost->save();
                    array_push($costIds,$a->cost_id);
                }
            } catch (Exception $e) {
                logger($e->getMessage());
            }
            foreach ($costs as $trans) {
                if (in_array($trans->language_id, [1, 2, 3]) && $a->cost_id == $trans->cost_id) {
                    $translation = new CostTranslation();
                    $translation->cost_id = $cost->id;
                    $translation->name = $trans->cost_des;
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
 * SELECT c.cost_id,c.sortorder,c.pay_available,c.fl_mandatory,c.fl_pay_to_owner,c.cost_unit,c.cost_season_type,ts.cost_des_id,ts.cost_des_id,ts.language_id,ts.cost_des  FROM `t_cost` c
left join `t_cost_des`  ts on c.cost_id = ts.cost_id 
WHERE `origin` = ' '
and ts.language_id in (1,2,3);
 */