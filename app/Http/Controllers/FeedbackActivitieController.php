<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\FeedbackActivitie;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreFeedbackActivitiesRequest;
use App\Http\Requests\UpdateFeedbackActivitiesRequest;

class FeedbackActivitieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $FeedbackActivitie = FeedbackActivitie::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
        )
            ->where('user_id', $user->id)
            ->get();

        if ($FeedbackActivitie->isEmpty()) {
            return response()->json(['message' => 'Este usuário não possui registros de mapas personalizados'], 404);
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

        return response()->json($FeedbackActivitieMap, 200);
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
         // Autenticar o usuário
         $user = JWTAuth::parseToken()->authenticate();

         $coordinates = $request->geometry;

         $FeedbackActivitie = new FeedbackActivitie();
 
         $FeedbackActivitie->user_id = $user->id;
         $FeedbackActivitie->name = $request->name;
         $FeedbackActivitie->subclass_id = $request->subclass_id;
         $FeedbackActivitie->region_id = $request->region_id;
         $FeedbackActivitie->geometry =DB::raw("ST_GeomFromText('POINT($coordinates[0] $coordinates[1])',0)");
 
         // verificação de region
        // $region_id = Region::whereRaw("ST_Contains(geometry, ST_GeomFromText('POINT($coordinates[0] $coordinates[1])',0))")
        // ->pluck('id');
 
         // Salvar o modelo no banco de dados
         if ($FeedbackActivitie->save()) {
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
        $user = JWTAuth::parseToken()->authenticate();

        $FeedbackActivitie = FeedbackActivitie::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
        )
            ->where('user_id', $user->id)
            ->find($id);

        if (!$FeedbackActivitie) {
            return response()->json(['message' => 'Este usuário não possui registros de mapas personalizados'], 404);
        }

        $FeedbackActivitieMap = [
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

        return response()->json($FeedbackActivitieMap, 200);
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
         $user = JWTAuth::parseToken()->authenticate();

         $validatedData = $request->validated(); 
         $coordinates = $request->geometry;

         $FeedbackActivitie = FeedbackActivitie::find($id);
 
         $FeedbackActivitie->user_id = $user->id;
         $FeedbackActivitie->name = $request->name;
         $FeedbackActivitie->subclass_id = $request->subclass_id;
         $FeedbackActivitie->region_id = $request->region_id;
         $FeedbackActivitie->geometry =DB::raw("ST_GeomFromText('POINT($coordinates[0] $coordinates[1])',0)");
 
        // Atualize os outros campos relevantes do modelo com os dados validados
        $FeedbackActivitie->fill($validatedData);

        // Salve as alterações no banco de dados
        if ($FeedbackActivitie->save()) {
            return response()->json(['message' => 'Atividade atualizada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao atualizar atividade'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $FeedbackActivitie = FeedbackActivitie::find($id);

        if (!$FeedbackActivitie) {
            return response()->json(['message' => 'Atividade não encontrada'], 404);
        }

        if ($FeedbackActivitie->delete()) {
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao deletar'], 500);
        }
    }
}
