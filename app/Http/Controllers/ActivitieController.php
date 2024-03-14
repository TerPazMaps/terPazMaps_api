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
        $activities = Activitie::select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->orderBy('id');

        if ($request->regions)
            $activities = $activities->whereIn('region_id', $request->regions);

        if ($request->subclasses)
            $activities = $activities->whereIn('subclass_id', $request->subclasses);

        if ($request->ids)
            $activities = $activities->whereIn('id', $request->ids);

        $activities = $activities->get();

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
                    $geojson_activity = [
                        "type" => "Feature",
                        "geometry" => json_decode($activity->geometry),
                        "properties" => [
                            "ID Geral" => $activity->id,
                            "Nome" => $activity->name ?? '',
                            "Classe" => $activity->subclass->class->name,
                            "Sub-classe" => $activity->subclass->name,
                            "Bairro" => $activity->region->name,
                            "Nível" => $activity->level
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
