<?php

namespace App\Http\Controllers;

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
        $user = JWTAuth::parseToken()->authenticate();

        $FeedbackStreet = FeedbackStreet::where('user_id', $user->id)
            ->get();

        if ($FeedbackStreet->isEmpty()) {
            return response()->json(['message' => 'Este usuário não possui registros feedback de ruas'], 404);
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
        $user = JWTAuth::parseToken()->authenticate();

        $feedback = new FeedbackStreet();
        $feedback->fill($request->validated());
        $feedback['user_id'] = $user->id;

        // Salvar o modelo no banco de dados
        if ($feedback->save()) {
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

        $feedbackStreet = FeedbackStreet::find($id);

        // dd($feedbackStreets);
        if ($feedbackStreet->count() === 0) {
            return response()->json(['message' => 'Este usuário não possui registros feedback de ruas'], 404);
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
        // Busca o modelo FeedbackStreet pelo ID
        $feedbackStreet = FeedbackStreet::find($id);
    
        // Verifica se o modelo foi encontrado
        if (!$feedbackStreet) {
            return response()->json(['message' => 'Feedback de rua não encontrado'], 404);
        }
    
        // Verifica se o usuário autenticado é o proprietário do feedback de rua
        $user = JWTAuth::parseToken()->authenticate();
        if ($feedbackStreet->user_id !== $user->id) {
            return response()->json(['message' => 'Você não tem permissão para atualizar este registro'], 403);
        }
    
        // Valida os dados da solicitação
        $validatedData = $request->validated(); 
    
        // Preenche o modelo com os dados validados e tenta salvar
        if ($feedbackStreet->fill($validatedData)->save()) {
            return response()->json(['message' => 'Feedback de rua atualizado com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao atualizar feedback de rua'], 500);
        }
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $feedbackStreet = FeedbackStreet::find($id);

        if (!$feedbackStreet) {
            return response()->json(['message' => 'Feedback de rua não encontrado'], 404);
        }

        if ($feedbackStreet->delete()) {
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Erro ao deletar'], 500);
        }
    }
}
