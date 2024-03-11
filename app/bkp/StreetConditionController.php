<?php

namespace App\Http\Controllers;

use App\Models\Street;
use Illuminate\Http\Request;
use App\Models\StreetCondition;
use Illuminate\Support\Facades\DB;

class StreetConditionController extends Controller
{


    public function index()
    {
        $streetConditions = Street::join('street_conditions', 'streets.street_condition_id', '=', 'street_conditions.id')
            ->select( 'street_conditions.condition', DB::raw('AsText(streets.geometry) as geometry'))
            ->where('streets.street_condition_id', 4)
            ->get();

        // Converte os dados para UTF-8
        $streetConditions->transform(function ($streetCondition) {
            foreach ($streetCondition->getAttributes() as $key => $value) {
                if ($key === 'properties') {
                    // Trata o campo 'properties' como JSON
                    $streetCondition->{$key} = json_decode($value, true);
                    // Converte os valores para UTF-8
                    $streetCondition->{$key} = array_map(function ($propValue) {
                        return mb_convert_encoding($propValue, 'UTF-8', 'UTF-8');
                    }, $streetCondition->{$key});
                } else {
                    $streetCondition->{$key} = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }
            }
            return $streetCondition;
        });

        return response()->json($streetConditions);
    }
}
