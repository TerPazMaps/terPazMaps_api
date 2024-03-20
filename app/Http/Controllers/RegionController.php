<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use App\Models\Region;
use App\Models\Street;
use App\Models\Subclasse;
use Illuminate\Http\Request;
use App\Policies\SubclassePolicy;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Models\Activitie;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::select(
            'id',
            'name',
            'city',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->get()
            ->map(function ($region) {
                $geojson_region = [
                    "type" => "Feature",
                    "geometry" => json_decode($region->geometry),
                    "properties" => [
                        "ID" => $region->id,
                        "Nome" => $region->name,
                        "Centro" => json_decode($region->center)
                    ]
                ];
                return $geojson_region;
            });

        $geojson = [
            "type" => "FeatureCollection",
            "features" => $regions
        ];
        header('Content-Type: application/json');

        echo json_encode($geojson);
    }

    public function getIconsByRegion(int $id, Request $request)
    {
        // Consulta para recuperar todas as atividades da região específica com suas subclasses relacionadas
        $activities = Activitie::where('region_id', $id)
        ->select(
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            )
            ->with('subclass.icon') // Carregar as subclasses e os ícones relacionados
            ->has('subclass.icon') // Garantir que apenas atividades com ícones relacionados sejam recuperadas
            ->get();

        // Array para armazenar os dados das atividades com ícones
        $activitiesData = [];

        foreach ($activities as $activity) {
            $activityData = [
                'id' => $activity->id,
                'name' => $activity->name,
                'geometry' => json_decode($activity->geometry),
                // Adicione outras propriedades da atividade conforme necessário
            ];

            // Verifica se a atividade tem uma subclasse e um ícone associado
            if ($activity->subclass && $activity->subclass->icon) {
                $icon = $activity->subclass->icon;
                // Construa a URL da imagem do ícone
                $imageUrl = config('app.url') . '/storage/' . substr($icon->disk_name, 0, 3) . '/' . substr($icon->disk_name, 3, 3) . '/' . substr($icon->disk_name, 6, 3) . '/' . $icon->disk_name;
                // Adicione a URL da imagem ao array de dados da atividade
                $activityData['img_url'] = $imageUrl;
            }

            // Adicione os dados da atividade ao array de atividades
            $activitiesData[] = $activityData;
        }

        // Converta o array de dados das atividades em JSON
        $jsonData = json_encode($activitiesData);

        // Exiba o JSON
       echo  $jsonData;
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
    public function store(StoreRegionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $regions = Region::select(
            'id',
            'name',
            'city',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
            DB::raw('ST_AsGeoJSON(center) as center')
        )
            ->where('id', $id)
            ->first();

        $geojson_region = [
            "type" => "Feature",
            "geometry" => json_decode($regions->geometry),
            "properties" => [
                "ID" => $regions->id,
                "Nome" => $regions->name,
                "Centro" => json_decode($regions->center)
            ]
        ];

        header('Content-Type: application/json');

        echo json_encode($geojson_region);
    }

    /**
     * Display the streets within the specified region as GeoJSON format.
     *
     * @param int $id The ID of the region.
     * @param \Illuminate\Http\Request $request The request object.
     * @return \Illuminate\Http\JsonResponse The GeoJSON representation of streets within the region.
     */
    public function getStreetsByRegion(int $id, Request $request)
    {
        $query = Street::select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry')
        )
            ->where('region_id', $id)
            ->with('streetCondition');

        // Verifica se o parâmetro 'condition_id' está presente na solicitação
        if ($request->condition_id) {
            $condition_ids = $request->condition_id ? array_map('intval', explode(',', $request->condition_id)) : [];
            // Aplica o filtro para 'condition_id'
            $query->whereIn('street_condition_id', $condition_ids);
        }

        $streets = $query->get()->map(function ($street) {
            $geometry = json_decode($street->geometry);
            $coordinates = $geometry->coordinates;
            $type = $geometry->type;

            $properties = [
                "id" => $street->id,
                "region_id" => $street->region_id,
                "condition" => $street->streetCondition->condition,
                "condition_id" => $street->streetCondition->id,
                "color" => $street->color,
                "with" => $street->with,
                "continuous" => $street->continuous,
                "line_cap" => $street->line_cap,
                "line_dash_pattern" => $street->line_dash_pattern,
                "name" => $street->name, // Adicionado o nome da rua como propriedade
            ];

            // Cria o objeto GeoJSON Feature
            $feature = [
                "type" => "Feature",
                "geometry" => [
                    "type" => $type,
                    "coordinates" => $coordinates
                ],
                "properties" => $properties,
            ];

            return $feature;
        });

        // Cria o objeto GeoJSON FeatureCollection
        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $streets->toArray(),
        ];

        return $featureCollection;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
    }
}
