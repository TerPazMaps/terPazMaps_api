<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function allRegions()
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

    public function onlyRegion($id)
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
}
