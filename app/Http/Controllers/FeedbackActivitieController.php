<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\FeedbackActivitie;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreFeedbackActivitiesRequest;
use App\Http\Requests\UpdateFeedbackActivitiesRequest;
use App\Policies\FeedbackActivitiesPolicy;

class FeedbackActivitieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $FeedbackActivitie = FeedbackActivitie::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            )
                ->where('user_id', $user->id)
                ->get();

            if ($FeedbackActivitie->isEmpty()) {
                return response()->json([
                    "error" => [
                        "status" => "404", "title" => "Not Found", "detail" => "Este usuário não possui registros"
                    ]
                ], 404);
            }

            $FeedbackActivitieMap = $FeedbackActivitie->map(function ($FeedbackActivitie) {
                return [
                    "type" => "Feature",
                    "geometry" => json_decode($FeedbackActivitie->geometry),
                    "properties" => [
                        "id" => $FeedbackActivitie->id,
                        "user_id" => $FeedbackActivitie->user_id,
                        "name" => $FeedbackActivitie->name,
                        "region_id" => $FeedbackActivitie->region_id,
                        "subclass_id" => $FeedbackActivitie->subclass_id,
                        "created_at" => Carbon::parse($FeedbackActivitie->created_at)->format('d/m/Y H:i:s'),
                        "updated_at" => Carbon::parse($FeedbackActivitie->updated_at)->format('d/m/Y H:i:s'),
                    ]
                ];
            });

            return response()->json([
                "success" => [
                    "status" => "200",
                    "title" => "OK",
                    "detail" => ["geojson" => $FeedbackActivitieMap],
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
    public function store(StoreFeedbackActivitiesRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackActivitie = new FeedbackActivitie();
            $coordinates = $request->geometry;

            $FeedbackActivitie->user_id = $user->id;
            $FeedbackActivitie->name = $request->name;
            $FeedbackActivitie->subclass_id = $request->subclass_id;
            $FeedbackActivitie->region_id = $request->region_id;
            $FeedbackActivitie->geometry = DB::raw("ST_GeomFromText('POINT($coordinates[0] $coordinates[1])',0)");

            if ($FeedbackActivitie->save()) {
                return response()->json([
                    "success" => [
                        "status" => "201", "title" => "Created", "detail" => "Salvo com sucesso"
                    ]
                ], 201);
            } else {
                return response()->json([
                    "error" => [
                        "status" => "500", "title" => "Internal Server Error", "detail" => "Erro ao salvar"
                    ]
                ], 500);
            }
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
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackActivitie = FeedbackActivitie::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            )
                ->where('user_id', $user->id)
                ->find($id);

            if (!$FeedbackActivitie) {
                return response()->json([
                    "error" => [
                        "status" => "404", "title" => "Not Found", "detail" => "Registro não encontrado"
                    ]
                ], 404);
            }

            if ($user->id != $FeedbackActivitie->user_id) {
                return response()->json([
                    "error" => [
                        "status" => "403",
                        "title" => "Forbidden",
                        "detail" => "Usuário não tem permissão para acessar o registro",
                    ]
                ], 403);
            }

            $feedbackActivitieMap = [
                "type" => "Feature",
                "geometry" => json_decode($FeedbackActivitie->geometry),
                "properties" => [
                    "id" => $FeedbackActivitie->id,
                    "user_id" => $FeedbackActivitie->user_id,
                    "name" => $FeedbackActivitie->name,
                    "region_id" => $FeedbackActivitie->region_id,
                    "subclass_id" => $FeedbackActivitie->subclass_id,
                    "created_at" => Carbon::parse($FeedbackActivitie->created_at)->format('d/m/Y H:i:s'),
                    "updated_at" => Carbon::parse($FeedbackActivitie->updated_at)->format('d/m/Y H:i:s'),
                ]
            ];

            return response()->json([
                "success" => [
                    "status" => "200",
                    "title" => "OK",
                    "detail" => ["geojson" => $feedbackActivitieMap],
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
     * Show the form for editing the specified resource.
     */
    public function edit(FeedbackActivitie $feedbackActivities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedbackActivitiesRequest $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackActivitie = FeedbackActivitie::find($id);

            if (!$FeedbackActivitie) {
                return response()->json([
                    "error" => [
                        "status" => "404", "title" => "Not Found", "detail" => "Registro não encontrado"
                    ]
                ], 404);
            }

            if ($user->id != $FeedbackActivitie->user_id) {
                return response()->json([
                    "error" => [
                        "status" => "403",
                        "title" => "Forbidden",
                        "detail" => "Usuário não tem permissão para acessar o registro",
                    ]
                ], 403);
            }

            $validatedData = $request->validated();
            $coordinates = $request->geometry;


            $FeedbackActivitie->user_id = $user->id;
            $FeedbackActivitie->name = $request->name;
            $FeedbackActivitie->subclass_id = $request->subclass_id;
            $FeedbackActivitie->region_id = $request->region_id;
            $FeedbackActivitie->geometry = DB::raw("ST_GeomFromText('POINT($coordinates[0] $coordinates[1])',0)");

            // Atualize os outros campos relevantes do modelo com os dados validados
            $FeedbackActivitie->fill($validatedData);

            // Salve as alterações no banco de dados
            if ($FeedbackActivitie->save()) {
                return response()->json([
                    "success" => [
                        "status" => "200", "title" => "OK", "detail" => "Atualizado com sucesso"
                    ]
                ], 200);
            } else {
                return response()->json([
                    "error" => [
                        "status" => "500", "title" => "Internal Server Error", "detail" => "Erro ao atualizar"
                    ]
                ], 500);
            }
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackActivitie = FeedbackActivitie::find($id);
                
            if (!$FeedbackActivitie) {
                return response()->json([
                    "error" => [
                        "status" => "404",
                        "title" => "Not Found",
                        "detail" => "Registro não encontrado",
                    ]
                ], 404);
            }

            if ($user->id != $FeedbackActivitie->user_id) {
                return response()->json([
                    "error" => [
                        "status" => "403",
                        "title" => "Forbidden",
                        "detail" => "Usuário não tem permissão para acessar o registro",
                    ]
                ], 403);
            }

            if ($FeedbackActivitie->delete()) {
                return response()->json([
                    "success" => [
                        "status" => "200", "title" => "OK", "detail" => "Deletado com sucesso"
                    ]
                ], 200);
            } else {
                return response()->json([
                    "error" => [
                        "status" => "500", "title" => "Internal Server Error", "detail" => "Erro ao deletar"
                    ]
                ], 500);
            }
            
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
}
