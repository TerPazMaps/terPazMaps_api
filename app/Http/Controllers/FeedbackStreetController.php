<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\FeedbackStreet;
use Illuminate\Support\Carbon;
use App\Http\Services\ApiServices;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreFeedbackStreetRequest;
use App\Http\Requests\UpdateFeedbackStreetRequest;

class FeedbackStreetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $FeedbackStreet = FeedbackStreet::where('user_id', $user->id)
                ->get();

            if ($FeedbackStreet->isEmpty()) {
                return ApiServices::statuscode404("Este usuário não possui registros");
            }

            $FeedbackStreetMap = $FeedbackStreet->map(function ($FeedbackStreet) {
                return [
                    "id" => $FeedbackStreet->id,
                    "user_id" => $FeedbackStreet->user_id,
                    "street_id" => $FeedbackStreet->street_id,
                    "street_condition_id" => $FeedbackStreet->street_condition_id,
                    "created_at" => Carbon::parse($FeedbackStreet->created_at)->format('d/m/Y H:i:s'),
                    "updated_at" => Carbon::parse($FeedbackStreet->updated_at)->format('d/m/Y H:i:s')
                ];
            });

            return ApiServices::statuscode200(["geojson" => $FeedbackStreetMap]);
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

            $feedbackStreetMaps = [
                "id" => $feedbackStreet->id,
                "user_id" => $feedbackStreet->user_id,
                "street_id" => $feedbackStreet->street_id,
                "street_condition_id" => $feedbackStreet->street_condition_id,
                "created_at" => Carbon::parse($feedbackStreet->created_at)->format('d/m/Y H:i:s'),
                "updated_at" => Carbon::parse($feedbackStreet->updated_at)->format('d/m/Y H:i:s')
            ];

            return ApiServices::statuscode200(["geojson" => $feedbackStreetMaps]);
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
