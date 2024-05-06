<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\UserCustomMap;
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
        $user = JWTAuth::parseToken()->authenticate();

        $mapas = UserCustomMap::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->where('user_id', $user->id)
            ->get();

        if ($mapas->isEmpty()) {
            return response()->json(['message' => 'Este usuário não possui registros de mapas personalizados'], 404);
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

        return response()->json($mapasTransformados, 200);
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
        // Autenticar o usuário
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
        $userCustomMap->center = DB::raw("ST_GeomFromText('POINT($longitude $latitude)',0)");
        // $userCustomMap->center = DB::raw("ST_GeomFromText('POINT($center[0] $center[1])',0)");

        // Salvar o modelo no banco de dados
        if ($userCustomMap->save()) {
            return response()->json(['message' => 'Salvo com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao salvar'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // O Laravel injeta automaticamente o objeto UserCustomMap com base no ID fornecido na rota
        $mapa = UserCustomMap::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->find($id);

        if (!$mapa) {
            return response()->json(['message' => 'Mapa não encontrado'], 404);
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

        return response()->json($geojson_mapa, 200);
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
    public function update(UpdateUserCustomMapRequest $request, UserCustomMap $user_custom_map)
    {
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
        $user_custom_map->fill($validatedData);

        // Salve as alterações no banco de dados
        if ($user_custom_map->save()) {
            return response()->json(['message' => 'Mapa personalizado do usuário atualizado com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao atualizar o mapa personalizado do usuário'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mapa = UserCustomMap::find($id);

        if (!$mapa) {
            return response()->json(['message' => 'Mapa não encontrado'], 404);
        }

        if ($mapa->delete()) {
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao deletar'], 500);
        }
    }
}
