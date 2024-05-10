<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Street_condition;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreStreet_conditionRequest;
use App\Http\Requests\UpdateStreet_conditionRequest;

class StreetConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $streetConditions = Street_condition::all();

            return response()->json([
                "success" => [
                    "status" => "200",
                    "title" => "OK",
                    "detail" => ["geojson" => $streetConditions],
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "error" => [
                    "status" => "500",
                    "title" => "Internal Server Error",
                    "detail" => $e->getMessage(),
                ]
            ], 500);
        }
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
        //
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
