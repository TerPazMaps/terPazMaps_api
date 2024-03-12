<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::select(
            'id',
            'name',
            'city',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->get()
            ->map(function ($region) {
                $geojson_region = [
                    "type" => "Feature",
                    "geometry" => json_decode($region->geometry),
                    "properties" => [
                        "ID" => $region->id,
                        "Nome" => $region->name,
                        "Centro" => json_decode($region->center)
                    ]
                ];
                return $geojson_region;
            });

        $geojson = [
            "type" => "FeatureCollection",
            "features" => $regions
        ];
        header('Content-Type: application/json');

        echo json_encode($geojson);
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
    public function store(StoreRegionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $id)
    {
        $regions = Region::select(
            'id',
            'name',
            'city',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->where('id', $id)
            ->first();

        $geojson_region = [
            "type" => "Feature",
            "geometry" => json_decode($regions->geometry),
            "properties" => [
                "ID" => $regions->id,
                "Nome" => $regions->name,
                "Centro" => json_decode($regions->center)
            ]
        ];

        header('Content-Type: application/json');

        echo json_encode($geojson_region);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
    }
}
