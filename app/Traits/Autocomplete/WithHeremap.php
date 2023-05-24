<?php

namespace App\Traits\Autocomplete;

use App\Data\AutocompleteData;
use geoPHP;

trait WithHeremap
{
    public function formatSuggestions($suggestions, $onlyLevel = null)
    {
        $result = [];
        foreach ($suggestions as  $value) {
            $title = null;
            $subtitle = null;
            switch ($value->matchLevel) {
                case 'houseNumber':
                    $title = $value->address->street . ' ' . $value->address->houseNumber . ', ' . $value->address->postalCode;
                    $subtitle = $value->address->city . ' ' . ' ' . $value->address->country . ', ' . $value->matchLevel;
                    break;
                case 'street':
                    $title = $value->address->street . ', ' . $value->address->postalCode;
                    $subtitle = $value->address->city . ' ' . ' ' . $value->address->country . ', ' . $value->matchLevel;
                    break;
                case 'district':
                    $title = $value->address->city . ($value->address->postalCode ? ', ' . $value->address->postalCode : '');
                    $subtitle = ($value->address->county ? $value->address->county . ' ' : '') . $value->address->country . ', ' . $value->matchLevel;
                    break;
                case 'city':
                    $title = $value->address->city . ($value->address->postalCode ? ', ' . $value->address->postalCode : '');
                    $subtitle = ($value->address->county ? $value->address->county . ' ' : '') . $value->address->country . ', ' . $value->matchLevel;
                    break;
                case 'county':
                    $title = $value->address->county;
                    $subtitle = ($value->address->state ? $value->address->state . ' ' : '') . $value->address->country . ', ' . $value->matchLevel;
                    break;
                case 'state':
                    $title = $value->address->state;
                    $subtitle = $value->address->country . ', ' . $value->matchLevel;
                    break;
                case 'country':
                    $title = $value->address->country;
                    $subtitle = $value->label . ', ' . $value->matchLevel;
                    break;

                default:
                    # code...
                    break;
            }
            $autocomplete = AutocompleteData::from([
                'id' => $value->label,
                'title' => $title,
                'subtitle' => $subtitle,
            ]);
            if($onlyLevel && in_array($value->matchLevel,$onlyLevel)) {
                $result[] = $autocomplete;
            } else if(!$onlyLevel) {
                $result[] = $autocomplete;
            }
        }
        return $result;
    }
    public function toWKT ($polygon) {
        $geometry = geoPHP::load(json_encode($polygon), 'json');
        return $geometry->out('wkt');
    }
    public function toJSON ($polygon) {
        $geometry = geoPHP::load($polygon,'wkt');
        return $geometry->out('json');
    }
}