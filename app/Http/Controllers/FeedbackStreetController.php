<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\ApiServices;
use App\Models\FeedbackStreet;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\FeedbackStreetService;
use App\Http\Requests\StoreFeedbackStreetRequest;
use App\Http\Requests\UpdateFeedbackStreetRequest;

class FeedbackStreetController extends Controller
{
    protected $feedbackStreetService;

    public function __construct()
    {
        $this->feedbackStreetService = new FeedbackStreetService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackStreet = $this->feedbackStreetService->index($user->id);

            if ($FeedbackStreet->isEmpty()) {
                return ApiServices::statuscode404("Este usuário não possui registros");
            }

            $FeedbackStreetMap = $this->feedbackStreetService->index_map($FeedbackStreet);

            return ApiServices::statuscode200($FeedbackStreetMap);
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
    public function store(StoreFeedbackStreetRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $feedback = new FeedbackStreet();
            $feedback['user_id'] = $user->id;
            $feedback->fill($request->validated());

            if ($feedback->save()) {
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
            $feedbackStreet = FeedbackStreet::find($id);

            if (!$feedbackStreet) {
                return ApiServices::statuscode404("Registro não encontrado");
            }

            if ($user->id != $feedbackStreet->user_id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
            }

            $feedbackStreetMaps = $this->feedbackStreetService->show_map($feedbackStreet);
               
            return ApiServices::statuscode200($feedbackStreetMaps);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeedbackStreet $feedbackStreet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedbackStreetRequest $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $feedbackStreet = FeedbackStreet::find($id);

            if (!$feedbackStreet) {
                return ApiServices::statuscode404("Registro não encontrado");
            }

            if ($feedbackStreet->user_id !== $user->id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
            }
            
            $validatedData = $request->validated();
            
            if ($feedbackStreet->fill($validatedData)->save()) {
                return ApiServices::statuscode200("Atualizada com sucesso");
            } else {
                return ApiServices::statuscode500("Atualizada com sucesso");
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
            $feedbackStreet = FeedbackStreet::find($id);

            if (!$feedbackStreet) {
                return ApiServices::statuscode404("Registro não encontrado");
            }

            if ($feedbackStreet->user_id !== $user->id) {
                return ApiServices::statuscode403("Usuário não tem permissão para acessar o registro");
            }
            
            if ($feedbackStreet->delete()) {
                return ApiServices::statuscode200("Usuário não tem permissão para acessar o registro");
            }
            
            return ApiServices::statuscode500("Erro ao deletar");
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }
}
