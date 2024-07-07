<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\FeedbackActivitie;
use App\Http\Services\ApiServices;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Policies\FeedbackActivitiesPolicy;
use App\Http\Requests\StoreFeedbackActivitiesRequest;
use App\Http\Requests\UpdateFeedbackActivitiesRequest;

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
                return ApiServices::statuscode404("Este usuário não possui registros");
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

            return ApiServices::statuscode200(["geojson" => $FeedbackActivitieMap]);
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
            $FeedbackActivitie->geometry = DB::raw("ST_GeomFromText('POINT($coordinates[0] $coordinates[1])',4326)");

            if ($FeedbackActivitie->save()) {
                return ApiServices::statuscode201("Salvo com sucesso");
            } else {
                return ApiServices::statuscode500("Erro ao salvar");
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
            $FeedbackActivitie = FeedbackActivitie::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            )
                ->where('user_id', $user->id)
                ->find($id);

            if (!$FeedbackActivitie) {
                return ApiServices::statuscode404("Registro não encontrado");
            }

            if ($user->id != $FeedbackActivitie->user_id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
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

            return ApiServices::statuscode200(["geojson" => $feedbackActivitieMap]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
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
                return ApiServices::statuscode404("Registro não encontrado");
            }

            if ($user->id != $FeedbackActivitie->user_id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
            }

            $validatedData = $request->validated();
            $coordinates = $request->geometry;


            $FeedbackActivitie->user_id = $user->id;
            $FeedbackActivitie->name = $request->name;
            $FeedbackActivitie->subclass_id = $request->subclass_id;
            $FeedbackActivitie->region_id = $request->region_id;
            $FeedbackActivitie->geometry = DB::raw("ST_GeomFromText('POINT($coordinates[0] $coordinates[1])', 4326)");

            // Atualize os outros campos relevantes do modelo com os dados validados
            $FeedbackActivitie->fill($validatedData);

            // Salve as alterações no banco de dados
            if ($FeedbackActivitie->save()) {
                return ApiServices::statuscode201("Atualizado com sucesso");
            } else {
                return ApiServices::statuscode500("Erro ao atualizar");
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
            $FeedbackActivitie = FeedbackActivitie::find($id);

            if (!$FeedbackActivitie) {
                return ApiServices::statuscode404("Registro não encontrado");
            }
            
            if ($user->id != $FeedbackActivitie->user_id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
            }
            
            if ($FeedbackActivitie->delete()) {
                return ApiServices::statuscode200("Deletado com sucesso");
            } else {
                return ApiServices::statuscode500("Erro ao deletar");
            }
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }
}
