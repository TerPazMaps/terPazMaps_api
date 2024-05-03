<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Street;
use App\Models\Activitie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;

class RegionController extends Controller
{
    private $redis_ttl;

    public function __construct()
    {
        $this->redis_ttl = 3600;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chaveCache = "RegionController_index";
        $regions = Cache::remember($chaveCache, $this->redis_ttl, function () {
            return Region::select(
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
        });

        $geojson = [
            "type" => "FeatureCollection",
            "features" => $regions
        ];
        header('Content-Type: application/json');

        return response()->json($geojson, 200);
    }

    public function getIconsByRegion(int $id, Request $request)
    {
        $chaveCache = "RegionController_getIconsByRegion_" . $id;
        if ($request->class_id) {
            $chaveCache .= "_" . $request->class_id;
            $class_ids = array_map('intval', explode(',', $request->class_id));
        }
        if ($request->subclass_id) {
            $chaveCache .= "_" . $request->subclass_id;
            $subclass_id = array_map('intval', explode(',', $request->subclass_id));
        }

        $activities = Cache::remember($chaveCache, $this->redis_ttl, function () use ($request, $id) {
            return Activitie::with(['subclass.icon'])
                ->whereHas('subclass', function ($query) use ($request) {
                    if ($request->class_id) {
                        $class_ids = array_map('intval', explode(',', $request->class_id));
                        $query->whereIn('class_id', $class_ids);
                    }
                })
                ->where('region_id', $id)
                ->where(function ($query) use ($request) {
                    if ($request->subclass_id) {
                        $subclass_id = array_map('intval', explode(',', $request->subclass_id));
                        $query->whereIn('subclass_id', $subclass_id);
                    }
                })
                ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                ->get();
        });

        $geojsonFeatures = [];

        foreach ($activities as $activity) {
            $geometry = json_decode($activity->geometry);

            // Construa a URL da imagem do ícone
            $imageUrl = 'http://127.0.0.1:8000/storage/' . substr($activity->subclass->icon->disk_name, 0, 3) . '/' . substr($activity->subclass->icon->disk_name, 3, 3) . '/' . substr($activity->subclass->icon->disk_name, 6, 3) . '/' . $activity->subclass->icon->disk_name;

            $feature = [
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'subclass' => [
                        'id' => $activity->subclass->id,
                        'class_id' => $activity->subclass->class_id,
                        'name' => $activity->subclass->name,
                        'icon' => [
                            'id' => $activity->subclass->icon->id,
                            'sb_id' => $activity->subclass->icon->subclasse_id,
                            'disk_name' => $activity->subclass->icon->disk_name,
                            'file_name' => $activity->subclass->icon->file_name,
                            'file_size' => $activity->subclass->icon->file_size,
                            'content_type' => $activity->subclass->icon->content_type,
                            'is_public' => $activity->subclass->icon->is_public,
                            'sort_order' => $activity->subclass->icon->sort_order,
                            'img_url' => $imageUrl,
                        ],
                    ],
                ],
            ];

            $geojsonFeatures[] = $feature;
        }

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $geojsonFeatures,
        ];

        return response()->json($geojson, 200);
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
        $chaveCache = "IconController_show_" . $id;
        $region = Cache::remember($chaveCache, $this->redis_ttl, function () use ($id) {
            return Region::select(
                'id',
                'name',
                'city',
                DB::raw('ST_AsGeoJSON(geometry) as geometry'),
                DB::raw('ST_AsGeoJSON(center) as center')
            )
                ->find($id);
        });

        $geojson_region = [
            "type" => "Feature",
            "geometry" => json_decode($region->geometry),
            "properties" => [
                "ID" => $region->id,
                "Nome" => $region->name,
                "Cidade" => $region->city,
                "Centro" => json_decode($region->center)
            ]
        ];

        return response()->json($geojson_region, 200);
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
            ->has('streetCondition');

        // Verifica se o parâmetro 'condition_id' está presente na solicitação
        $chaveCache = "IconController_getStreetsByRegion_" . $id;
        if ($request->condition_id) {
            $condition_ids = $request->condition_id ? array_map('intval', explode(',', $request->condition_id)) : [];
            $chaveCache .= "_" . $request->condition_id;
            // Aplica o filtro para 'condition_id'
            $query->whereIn('street_condition_id', $condition_ids);
        }

        $streets = Cache::remember($chaveCache, $this->redis_ttl, function () use ($query) {
            return $query->get()->map(function ($street) {
                $geometry = json_decode($street->geometry);
                $coordinates = $geometry->coordinates;
                $type = $geometry->type;

                $decodedProperties = json_decode($street->properties, true);
                $properties = array_merge([
                    "id" => $street->id,
                    "region_id" => $street->region_id,
                    "condition" => $street->streetCondition->condition,
                    "condition_id" => $street->streetCondition->id,
                    "color" => $street->color,
                    "with" => $street->with,
                    "continuous" => $street->continuous,
                    "line_cap" => $street->line_cap,
                    "line_dash_pattern" => $street->line_dash_pattern,
                ], $decodedProperties);

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
        });

        // Cria o objeto GeoJSON FeatureCollection
        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $streets->toArray(),
        ];

        return response()->json($featureCollection, 200);
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
