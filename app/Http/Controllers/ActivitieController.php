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
    public function index()
    {
        $request = Request::create('/', 'POST', [
            'regions' => null, // Defina os IDs das regiões conforme necessário
            'subclasses' => null, // Defina os IDs das subclasses conforme necessário
            'name' => null, // Defina o nome da atividade conforme necessário
        ]);

        $activities = Activitie::select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->orderBy('id');

        if ($request->regions) {
            $activities = $activities->whereIn('region_id', $request->regions);
        }
        if ($request->subclasses) {
            $activities = $activities->whereIn('subclass_id', $request->subclasses);
        }
        if ($request->name) {
            $activities = $activities->where(function ($query) use ($request) {
                $query->where('name', 'like', $request->name . '%')
                    ->orWhere('name', 'like', '%' . $request->name . '%');
            });
        }
        $activities = $activities->get();

        // Construir a estrutura desejada para cada atividade
        $features = [];
        foreach ($activities as $activity) {
            $feature = [
                'type' => 'Feature',
                'properties' => [
                    'id' => $activity->id,
                    'region_id' => $activity->region_id,
                    'subclass_id' => $activity->subclass_id,
                    'name' => $activity->name,
                    'active' => $activity->active,
                    'level' => $activity->level,
                    'created_at' => $activity->created_at,
                    'updated_at' => $activity->updated_at
                ],
                'geometry' => json_decode($activity->geometry)
            ];
            $features[] = $feature;
        }

        // Construir a estrutura final
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        // Converter para JSON
        $jsonData = json_encode($geojson);

        // Retornar o JSON
        return $jsonData;
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
