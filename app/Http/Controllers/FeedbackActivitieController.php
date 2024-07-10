<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\ApiServices;
use App\Models\FeedbackActivitie;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\FeedbackActivitieService;
use App\Http\Requests\StoreFeedbackActivitiesRequest;
use App\Http\Requests\UpdateFeedbackActivitiesRequest;

class FeedbackActivitieController extends Controller
{
    protected $feedbackActivitieService;

    public function __construct()
    {
        $this->feedbackActivitieService = new FeedbackActivitieService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackActivitie = $this->feedbackActivitieService->index($user->id);
            
            if ($FeedbackActivitie->isEmpty()) {
                return ApiServices::statuscode404("Este usuário não possui registros");
            }

            $FeedbackActivitieMap = $this->feedbackActivitieService->index_map($FeedbackActivitie);

            return ApiServices::statuscode200($FeedbackActivitieMap);
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
            $FeedbackActivitie = $this->feedbackActivitieService->show($id);

            if (!$FeedbackActivitie) {
                return ApiServices::statuscode404("Registro não encontrado");
            }

            if ($user->id != $FeedbackActivitie->user_id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
            }

            $feedbackActivitieMap = $this->feedbackActivitieService->show_map($FeedbackActivitie);
                
            return ApiServices::statuscode200($feedbackActivitieMap);
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
