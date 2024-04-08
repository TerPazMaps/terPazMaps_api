<?php

namespace App\Http\Controllers;

use App\Models\Activitie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreActivitieRequest;
use App\Http\Requests\UpdateActivitieRequest;
// use GuzzleHttp\Psr7\Request;

class ActivitieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activities = Activitie::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry')
        )
            ->with('subclass.classe')
            ->with('subclass.icon')
            ->has('subclass.classe')
            ->orderBy('id');

        if ($request->regions) {
            $regions_id = $request->regions ? array_map('intval', explode(',', $request->regions)) : [];
            $activities = $activities->whereIn('region_id', $regions_id);
        }

        if ($request->subclasses) {
            $subclasses_id = $request->subclasses ? array_map('intval', explode(',', $request->subclasses)) : [];
            $activities = $activities->whereIn('subclass_id', $subclasses_id);
        }
        // dd($request->all());

        if ($request->ids) {
            $ids = $request->ids ? array_map('intval', explode(',', $request->ids)) : [];
            $activities = $activities->whereIn('id', $ids);
        }

        $activities = $activities->get();

        // dd($activities);
        if ($request->only_references) {
            $activities = $activities
                ->map(function ($activity) {
                    $geojson_activity = [
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
                ->map(function ($activity) {
                    // Construa a URL da imagem do ícone

                    $geojson_activity = [
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

        $geojson = [
            "type" => "FeatureCollection",
            "features" => $activities
        ];

        return $geojson;
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
