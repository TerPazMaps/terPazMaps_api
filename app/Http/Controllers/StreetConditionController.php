<?php

namespace App\Http\Controllers;

use App\Models\Street;
use App\Models\Street_condition;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStreet_conditionRequest;
use App\Http\Requests\UpdateStreet_conditionRequest;

class StreetConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $streetConditions = Street::join('street_conditions', 'streets.street_condition_id', '=', 'street_conditions.id')
            ->select('street_conditions.condition',
             DB::raw('AsText(streets.geometry) as geometry'))
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStreet_conditionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $streetConditions = Street::join('street_conditions', 'streets.street_condition_id', '=', 'street_conditions.id')
            ->select('street_conditions.condition',
             DB::raw('AsText(streets.geometry) as geometry'))
            ->where('streets.id', $id)
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Street_condition $street_condition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreet_conditionRequest $request, Street_condition $street_condition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Street_condition $street_condition)
    {
        //
    }
}
