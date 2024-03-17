<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\Street;
use Illuminate\Http\Request;

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
    public function show(int $id)
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
     * Display the streets within the specified region as GeoJSON format.
     *
     * @param int $id The ID of the region.
     * @param \Illuminate\Http\Request $request The request object.
     * @return \Illuminate\Http\JsonResponse The GeoJSON representation of streets within the region.
     */
    public function getStreetsByRegion(int $id, Request $request)
    {
        $query = Street::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry')
        )
            ->where('region_id', $id)
            ->with('streetCondition');


        // Verifica se o parâmetro 'condition_id' está presente na solicitação
        if ($request->condition_id) {
            $condition_ids = $request->condition_id ? array_map('intval', explode(',', $request->condition_id)) : [];
            // Aplica o filtro para 'condition_id'
            $query->whereIn('street_condition_id', $condition_ids);
        }

        $streets = $query->get()->map(function ($street) {
            $geometry = json_decode($street->geometry);
            $coordinates = $geometry->coordinates;
            $type = $geometry->type;

            if ($type === 'MultiLineString') {
                $type = 'LineString';
                $coordinates = $coordinates[0]; // Take the first LineString from MultiLineString
            }
            $properties = json_decode($street->properties, true);

            // Adiciona todas as propriedades desejadas de uma vez utilizando array_merge
            $properties = array_merge([
                "id" => $street->id,
                "region_id" => $street->region_id,
                "condition" => $street->streetCondition->condition,
                "condition_id" => $street->streetCondition->id,
                "color" => $street->color,
                "with" => $street->with,
                "continuous" => $street->continuous,
                "line_cap" => $street->line_cap,
                "line_dash_pattern" => $street->line_dash_pattern,
            ], $properties);

            $geojson_feature = [
                "type" => "Feature",
                "geometry" => [
                    "type" => $type,
                    "coordinates" => $coordinates
                ],
                "properties" => $properties,
            ];

            return $geojson_feature;
        });

        // Monta o GeoJSON FeatureCollection
        $geojson_collection = [
            "type" => "FeatureCollection",
            "features" => $streets->toArray()
        ];

        return response()->json($geojson_collection);
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
