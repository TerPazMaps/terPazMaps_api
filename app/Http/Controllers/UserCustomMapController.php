<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\UserCustomMap;
use App\Services\ApiServices;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreUserCustomMapRequest;
use App\Http\Requests\UpdateUserCustomMapRequest;

class UserCustomMapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // http://127.0.0.1:8000/api/v5/geojson/user-custom-maps
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $mapas = UserCustomMap::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry'),
                DB::raw('ST_AsGeoJSON(center) as center')
            )
                ->where('user_id', $user->id)
                ->get();

            if ($mapas->isEmpty()) {
                return ApiServices::statusCode404("Este usuário não possui registros");
            }

            $mapasTransformados = $mapas->map(function ($mapa) {
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

            return ApiServices::statusCode200(["geojson" => $mapasTransformados]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
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
    public function store(StoreUserCustomMapRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $coordinates = $request->geometry;

            $userCustomMap = new UserCustomMap();

            $userCustomMap->user_id = $user->id;
            $userCustomMap->name = $request->name;

            // Formate as coordenadas no formato correto (longitude latitude)
            $wktCoordinates = [];
            foreach ($coordinates[0] as $point) {
                $wktCoordinates[] = "{$point[0]} {$point[1]}";
            }

            $wktPolygon = "POLYGON((" . implode(",", $wktCoordinates) . "))";

            // calculo do centro 
            $sql = "SELECT ST_X(ST_Centroid(ST_GeomFromText('$wktPolygon'))) as x, ST_Y(ST_Centroid(ST_GeomFromText('$wktPolygon'))) as y";
            $center2 = DB::select($sql);
            $longitude = $center2[0]->x;
            $latitude = $center2[0]->y;

            $userCustomMap->geometry = DB::raw("ST_GeomFromText('$wktPolygon')");
            $userCustomMap->center = DB::raw("ST_GeomFromText('POINT($longitude $latitude)',4326)");
            // $userCustomMap->center = DB::raw("ST_GeomFromText('POINT($center[0] $center[1])',0)");

            // Salvar o modelo no banco de dados
            if ($userCustomMap->save()) {
                return ApiServices::statusCode201("Salvo com sucesso");
            } else {
                return ApiServices::statusCode500("Erro ao salvar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapa = UserCustomMap::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry'),
                DB::raw('ST_AsGeoJSON(center) as center')
            )
                ->find($id);

            if (!$mapa) {
                return ApiServices::statusCode404("Registo do mapa personalizado não encontrado");
            }

            if ($user->id != $mapa->user_id) {
                return ApiServices::statusCode403("Usuário não tem permissão para acessar o registro");
            }

            $geojson_mapa = [
                "type" => "Feature",
                "geometry" => json_decode($mapa->geometry),
                "properties" => [
                    "ID" => $mapa->id,
                    "user_ID" => $mapa->user_id,
                    "Nome" => $mapa->name,
                    "Centro" => json_decode($mapa->center),
                    "created_at" => $mapa->created_at,
                    "updated_at" => $mapa->updated_at,
                ]
            ];

            return ApiServices::statusCode200(["geojson" => $geojson_mapa]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserCustomMap $user_custom_maps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserCustomMapRequest $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapa = UserCustomMap::find($id);
            if (!$mapa) {
                return ApiServices::statusCode404("Registo do mapa personalizado não encontrado");
            }

            if ($user->id != $mapa->user_id) {
                return ApiServices::statusCode403("Usuário não tem permissão para acessar o registro");
            }

            $validatedData = $request->validated();

            // Formate as coordenadas no formato correto (longitude latitude)
            $coordinates = $request->geometry;
            $wktCoordinates = [];
            foreach ($coordinates[0] as $point) {
                $wktCoordinates[] = "{$point[0]} {$point[1]}";
            }
            $wktPolygon = "POLYGON((" . implode(",", $wktCoordinates) . "))";

            // Atualize a geometria do mapa personalizado do usuário diretamente no modelo
            $validatedData['geometry'] = DB::raw("ST_GeomFromText('$wktPolygon')");
            // dd($validatedData['geometry']);

            // calculo do centro 
            $sql = "SELECT ST_X(ST_Centroid(ST_GeomFromText('$wktPolygon'))) as x, ST_Y(ST_Centroid(ST_GeomFromText('$wktPolygon'))) as y";
            $center2 = DB::select($sql);
            $longitude = $center2[0]->x;
            $latitude = $center2[0]->y;

            $validatedData['center'] = DB::raw("ST_GeomFromText('POINT($longitude $latitude)',0)");

            // Atualize os outros campos relevantes do modelo com os dados validados
            $mapa->fill($validatedData);

            // Salve as alterações no banco de dados
            if ($mapa->save()) {
                return ApiServices::statusCode200("Atualizado com sucesso");
            } else {
                return ApiServices::statusCode500("Erro ao atualizar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $mapa = UserCustomMap::find($id);

            if (!$mapa) {
                return ApiServices::statusCode404("Registro não encontrado");
            }

            if ($user->id != $mapa->user_id) {
                return ApiServices::statusCode403("Usuário não tem permissão para acessar o registro");
            }

            if ($mapa->delete()) {
                return ApiServices::statusCode200("Deletado com sucesso");
            } else {
                return ApiServices::statusCode500("Erro ao deletar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }
}
