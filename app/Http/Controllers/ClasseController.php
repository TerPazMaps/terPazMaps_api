<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Classe;
use App\Http\Services\ApiServices;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;

class ClasseController extends Controller
{
    private $redis_ttl;

    public function __construct()
    {
        $this->redis_ttl = 3600;
    }

    /**
     * Display a listing of the resource.
     */
    // http://127.0.0.1:8000/api/v5/geojson/classe/
    public function index()
    {
        try {
            $chaveCache = "ClasseController_index";
            $classes = Cache::remember($chaveCache, $this->redis_ttl, function () {
                return Classe::select(
                    'id',
                    'name',
                    'related_color',
                    'related_secondary_color'
                )
                    ->get()
                    ->map(function ($classe) {
                        $classe = [
                            "Classe" => [
                                "ID" => $classe->id,
                                "Nome" => $classe->name,
                                "related_color" => $classe->related_color,
                                "related_secondary_color" => $classe->related_secondary_color
                            ]
                        ];
                        return $classe;
                    });
            });

            return ApiServices::statusCode200(["geojson" => $classes]);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
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
    public function store(StoreClasseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $chaveCache = "ClasseController_show_" . $id;
            $classe = Cache::remember($chaveCache, $this->redis_ttl, function () use ($id) {
                return Classe::select(
                    'id',
                    'name',
                    'related_color',
                    'related_secondary_color'
                )->find($id);
            });

            if (!$classe) {
                return ApiServices::statuscode404("Classe não encontrada no banco de dados.");
            }

            return ApiServices::statuscode200(["geojson" => $classe]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function getSubclassesByClass(int $id)
    {
        try {
            $chaveCache = "ClasseController_getSubclassesByClass_" . $id;
            $classes = Cache::remember($chaveCache, $this->redis_ttl, function () use ($id) {
                $classes = Classe::where('id', $id)
                    ->has('subclasse')
                    ->has('subclasse.icon')
                    ->paginate(15);

                $baseUrl = config('app.url');
                foreach ($classes as $cl) {
                    foreach ($cl->subclasse as $subclasse) {
                        $icon = $subclasse->icon;
                        $icon->image_url = $baseUrl . 'storage/' . substr($icon->disk_name, 0, 3) . '/' . substr($icon->disk_name, 3, 3) . '/' . substr($icon->disk_name, 6, 3) . '/' . $icon->disk_name;
                    }
                }

                return $classes;
            });

            return ApiServices::statuscode200(["geojson" => $classes]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
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
