<?php

namespace App\Http\Controllers;

use App\Models\Activitie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreActivitieRequest;
use App\Http\Requests\UpdateActivitieRequest;
use Exception;

class ActivitieController extends Controller
{
    private $redis_ttl;

    public function __construct()
    {
        $this->redis_ttl = 3600;
    }

    /**
     * Display a listing of the resource.
     */
    // http://127.0.0.1:8000/api/v5/geojson/activities
    public function index(Request $request)
    {
        try {
            $activities = Activitie::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry')
            )
                ->has('subclass.classe')
                ->has('subclass.icon')
                ->orderBy('id');

            $chaveCache = "ActivitieController_index";

            // Construindo a chave do cache com base nos parâmetros da solicitação
            if ($request->regions) {
                $chaveCache .= "_regions_" . $request->regions;
                $regions_id = array_map('intval', explode(',', $request->regions));
                $activities = $activities->whereIn('region_id', $regions_id);
            }

            if ($request->subclasses) {
                $chaveCache .= "_subclasses_" . $request->subclasses;
                $subclasses_id = array_map('intval', explode(',', $request->subclasses));
                $activities = $activities->whereIn('subclass_id', $subclasses_id);
            }

            if ($request->ids) {
                $chaveCache .= "_ids_" . $request->ids;
                $ids = array_map('intval', explode(',', $request->ids));
                $activities = $activities->whereIn('id', $ids);
            }

            $startTime = microtime(true);
            $activities = Cache::remember($chaveCache, $this->redis_ttl, function () use ($activities) {
                return $activities->get();
            });

            $endTime = microtime(true);
            $executionTime = number_format(($endTime - $startTime) * 1000, 4);

            $chaveCache = "ActivitieController_index_map_" . $request->regions . $request->subclasses . $request->ids . $request->only_references;
            $activities = Cache::remember($chaveCache, $this->redis_ttl, function () use ($activities, $request, $executionTime) {
                if ($request->only_references) {
                    $activities = $activities
                        ->map(function ($activity) use ($executionTime) {
                            $geojson_activity = [
                                "time" => $executionTime,
                                "type" => "Feature",
                                "geometry" => json_decode($activity->geometry),
                                "properties" => [
                                    "ID Geral" => $activity->id,
                                    "Nome" => $activity->name ?? '',
                                    "ID Subclasse" => $activity->subclass->id,
                                    "ID Bairro" => $activity->region->id,
                                    "Nível" => $activity->level
                                ]
                            ];

                            return $geojson_activity;
                        });
                } else {
                    $activities = $activities
                        ->map(function ($activity) use ($executionTime) {
                            // Construa a URL da imagem do ícone

                            $geojson_activity = [
                                "time" => $executionTime,
                                "type" => "Feature",
                                "geometry" => json_decode($activity->geometry),
                                "properties" => [
                                    "ID Geral" => $activity->id,
                                    "Nome" => $activity->name ?? '',
                                    "Classe" => $activity->subclass->classe->name ?? '',
                                    "Sub-classe" => $activity->subclass->name,
                                    "Bairro_id" => $activity->region->id,
                                    "Bairro" => $activity->region->name,
                                    "Nível" => $activity->level,
                                    "img_url" => 'http://127.0.0.1:8000/storage/' . substr($activity->subclass->icon->disk_name, 0, 3) . '/' . substr($activity->subclass->icon->disk_name, 3, 3) . '/' . substr($activity->subclass->icon->disk_name, 6, 3) . '/' . $activity->subclass->icon->disk_name
                                ]
                            ];

                            return $geojson_activity;
                        });
                }
                return $activities;
            });

            $geojson = [
                "type" => "FeatureCollection",
                "features" => $activities
            ];

            return response()->json([
                "success" => [
                    "timeF" => $executionTime,
                    "status" => "200",
                    "title" => "OK",
                    "detail" => ["geojson" => $geojson],
                ]
            ], 200);
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
    public function store(StoreActivitieRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Activitie $activitie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activitie $activitie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivitieRequest $request, Activitie $activitie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activitie $activitie)
    {
        //
    }
}
