<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\FeedbackStreet;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controller;
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
                return response()->json([
                    "error" => [
                        "status" => "404", "title" => "Not Found", "detail" => "Este usuário não possui registros feedbacks de ruas"
                    ]
                ], 404);
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

            return response()->json($FeedbackStreetMap, 200);
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
    public function store(StoreFeedbackStreetRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $feedback = new FeedbackStreet();
            $feedback->fill($request->validated());
            $feedback['user_id'] = $user->id;

            if ($feedback->save()) {
                return response()->json([
                    "success" => ["status" => "201", "title" => "Created", "detail" => "Feedback salvo com sucesso"]
                ], 201);
            } else {
                return response()->json([
                    "error" => [
                        "status" => "500", "title" => "Internal Server Error", "detail" => "Erro ao tentar salvar o feedback"
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
            $feedbackStreet = FeedbackStreet::find($id);

            if ($feedbackStreet->count() === 0) {
                return response()->json([
                    "error" => ["status" => "404", "title" => "Not Found", "detail" => "Este usuário não possui registros feedback de ruas"]
                ], 404);
            }

            $feedbackStreetMaps = [
                "id" => $feedbackStreet->id,
                "user_id" => $feedbackStreet->user_id,
                "street_id" => $feedbackStreet->street_id,
                "street_condition_id" => $feedbackStreet->street_condition_id,
                "created_at" => Carbon::parse($feedbackStreet->created_at)->format('d/m/Y H:i:s'),
                "updated_at" => Carbon::parse($feedbackStreet->updated_at)->format('d/m/Y H:i:s')
            ];

            return response()->json($feedbackStreetMaps, 200);
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
            $feedbackStreet = FeedbackStreet::find($id);

            if (!$feedbackStreet) {
                return response()->json([
                    "error" => ["status" => "404", "title" => "Not Found", "detail" => "Feedback de rua não encontrado"]
                ], 404);
            }

            $user = JWTAuth::parseToken()->authenticate();
            if ($feedbackStreet->user_id !== $user->id) {
                return response()->json([
                    "error" => ["status" => "403", "title" => "Forbidden", "detail" => "Você não tem permissão para atualizar este registro"]
                ], 403);
            }

            $validatedData = $request->validated();

            if ($feedbackStreet->fill($validatedData)->save()) {
                return response()->json([
                    "success" => ["status" => "200", "title" => "OK", "detail" => "Feedback de rua atualizado com sucesso"]
                ], 403);
            } else {
                return response()->json([
                    "error" => ["status" => "500", "title" => "Internal Server Error", "detail" => "Erro ao atualizar feedback de rua"]
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
            $feedbackStreet = FeedbackStreet::find($id);

            if (!$feedbackStreet) {
                return response()->json([
                    "error" => ["status" => "404", "title" => "Not Found", "detail" => "Feedback de rua não encontrado"]
                ], 404);
            }

            if ($feedbackStreet->delete()) {
                return response()->json([
                    "success" => ["status" => "200", "title" => "OK", "detail" => "feedback de rua deletado com sucesso"]
                ], 200);
            }
        
            return response()->json([
                "error" => ["status" => "500", "title" => "Internal Server Error", "detail" => "Erro ao deletar feedback de rua"]
            ], 500);
        
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
