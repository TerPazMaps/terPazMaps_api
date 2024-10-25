<?php

namespace App\Services;

use App\Models\UserCustomMap;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Interfaces\ServiceInterface;

class UserCustomMapService extends AbstractService implements ServiceInterface
{
    protected static $model = UserCustomMap::class;

    public function index($user_id): Collection
    {
        return self::loadModel()->query()->select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->where('user_id', $user_id)
            ->get();
    }

    public function index_map($mapas): Collection
    {
        return $mapas->map(function ($mapa) {
            return [
                "type" => "Feature",
                "geometry" => json_decode($mapa->geometry),
                "properties" => [
                    "ID" => $mapa->id,
                    "user_ID" => $mapa->user_id,
                    "Nome" => $mapa->name,
                    "Centro" => json_decode($mapa->center),
                    "created_at" => Carbon::parse($mapa->created_at)->format('d/m/Y H:i:s'),
                    "updated_at" => Carbon::parse($mapa->updated_at)->format('d/m/Y H:i:s'),
                ]
            ];
        });
    }

    public function constructGeometryAndCenter($request): array
    {
        // Formate as coordenadas no formato correto (longitude latitude)
        $coordinates = $request->geometry;
        $wktCoordinates = [];
        foreach ($coordinates[0] as $point) {
            $wktCoordinates[] = "{$point[0]} {$point[1]}";
        }
        $wktPolygon = "POLYGON((" . implode(",", $wktCoordinates) . "))";

        // calculo do centro 
        $sql = "SELECT ST_X(ST_Centroid(ST_GeomFromText('$wktPolygon'))) as x, ST_Y(ST_Centroid(ST_GeomFromText('$wktPolygon'))) as y";
        // $userCustomMap->center = DB::raw("ST_GeomFromText('POINT($center[0] $center[1])',0)");
        $center2 = DB::select($sql);
        $longitude = $center2[0]->x;
        $latitude = $center2[0]->y;



        $geometry = DB::raw("ST_GeomFromText('$wktPolygon')");
        $center = DB::raw("ST_GeomFromText('POINT($longitude $latitude)',4326)");

        return [$geometry, $center];
    }

    public function show($id): UserCustomMap|null
    {
        return self::loadModel()->query()->select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )->find($id);
    }

    public function show_map($mapa): array
    {
        return [
            "geojson" => [
                "type" => "Feature",
                "geometry" => json_decode($mapa->geometry),
                "properties" => [
                    "ID" => $mapa->id,
                    "user_ID" => $mapa->user_id,
                    "Nome" => $mapa->name,
                    "Centro" => json_decode($mapa->center),
                    "created_at" => Carbon::parse($mapa->created_at)->format('d/m/Y H:i:s'),
                    "updated_at" => Carbon::parse($mapa->updated_at)->format('d/m/Y H:i:s'),
                ]
            ]
        ];
    }
}
