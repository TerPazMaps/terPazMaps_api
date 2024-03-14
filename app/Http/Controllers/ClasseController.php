<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use App\Models\Classe;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::select(
            'id',
            'name',
            'related_color',
            'related_secondary_color'
        )
            ->get()
            ->map(function ($classe) {
                $geojson_classe = [
                    "Classe" => [
                        "ID" => $classe->id,
                        "Nome" => $classe->name,
                        "related_color" => $classe->related_color,
                        "related_secondary_color" => $classe->related_secondary_color
                    ]
                ];
                return $geojson_classe;
            });

        header('Content-Type: application/json');

        echo json_encode($classes);
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
    public function store(StoreClasseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $classes = Classe::select(
            'id',
            'name',
            'related_color',
            'related_secondary_color'
        )
            ->where('id', $id)
            ->get()
            ->map(function ($classe) {
                $geojson_classe = [
                    "Classe" => [
                        "ID" => $classe->id,
                        "Nome" => $classe->name,
                        "related_color" => $classe->related_color,
                        "related_secondary_color" => $classe->related_secondary_color
                    ]
                ];
                return $geojson_classe;
            });

        header('Content-Type: application/json');

        echo json_encode($classes);
    }

    /**
     * Display the specified resource.
     */
    public function getSubclassesByClass(int $id)
    {
        $classe = Classe::where('id', $id)
            ->with('subclasse.icon')
            ->first();

        // Adicionar o link para a imagem em cada ícone
        $baseUrl = config('app.url');
        foreach ($classe->subclasse as $subclasse) {
            foreach ($subclasse->icon as $icon) {
                $icon->image_url = $baseUrl . '/storage/' . substr($icon->disk_name, 0, 3) . '/' . substr($icon->disk_name, 3, 3) . '/' . substr($icon->disk_name, 6, 3) . '/' . $icon->disk_name;
            }
        }

        header('Content-Type: application/json');

        return response()->json($classe);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classe $classe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClasseRequest $request, Classe $classe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classe)
    {
        //
    }
}
