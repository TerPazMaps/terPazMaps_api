<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use App\Models\Classe;
use App\Models\Subclasse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreSubclasseRequest;
use App\Http\Requests\UpdateSubclasseRequest;

class SubclasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Iniciar a consulta para recuperar todas as subclasses com ícones relacionados
        $subclassesQuery = Icon::with('subclasse')->has('subclasse');

        // Verificar se há um parâmetro 'name' na solicitação
        if ($request->has('name')) {
            $name = $request->input('name');

            // Adicionar a cláusula where para filtrar as subclasses pelo nome
            $subclassesQuery->whereHas('subclasse', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        // Executar a consulta
        $subclasses = $subclassesQuery->get();

        // Adicionar link para acessar a imagem a cada objeto
        $baseUrl = config('app.url');
        $subclasses->transform(function ($item, $key) use ($baseUrl) {
            $item['image_url'] = $baseUrl . '/storage/' . substr($item->disk_name, 0, 3) . '/' . substr($item->disk_name, 3, 3) . '/' . substr($item->disk_name, 6, 3) . '/' . $item->disk_name;
            return $item;
        });

        // Retornar os resultados em formato JSON
        return response()->json($subclasses);
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
    public function store(StoreSubclasseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subclasse $subclasse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subclasse $subclasse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubclasseRequest $request, Subclasse $subclasse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subclasse $subclasse)
    {
        //
    }
}
