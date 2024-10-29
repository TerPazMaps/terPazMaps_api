<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Region;
use App\Models\Street;
use App\Models\Activitie;
use Illuminate\Http\Request;
use App\Services\ApiServices;
use App\Services\RedisService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Services\GeospatialService;

class RegionController extends Controller
{
    private $redis_ttl;
    protected $redisService;

    public function __construct()
    {
        $this->redis_ttl = 3600;
        $this->redisService = new RedisService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
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

            return ApiServices::statuscode200(["geojson" => $geojson]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    public function getIconsByRegion(int $id, Request $request)
    {
        try {
            $prefix = "RegionController_getIconsByRegion_" . $id;
            if ($request->class_id) {
                $class_ids = array_map('intval', explode(',', $request->class_id));
            }
            if ($request->subclass_id) {
                $subclass_id = array_map('intval', explode(',', $request->subclass_id));
            }   

            $keyCache = $this->redisService->createKeyCacheFromRequest($request, $prefix, ['class_id', 'subclass_id']);
            $activities = Cache::remember($keyCache, $this->redis_ttl, function () use ($request, $id, $class_ids, $subclass_id) {
                
                return Activitie::with(['subclass.icon'])
                    ->whereHas('subclass', function ($query) use ($request, $class_ids) {
                        if ($request->class_id) {
                            $query->whereIn('class_id', $class_ids);
                        }
                    })
                    ->where('region_id', $id)
                    ->where(function ($query) use ($request, $subclass_id) {
                        if ($request->subclass_id) {
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
                $imageUrl = env('APP_URL') . 'storage/' . substr($activity->subclass->icon->disk_name, 0, 3) . '/' . substr($activity->subclass->icon->disk_name, 3, 3) . '/' . substr($activity->subclass->icon->disk_name, 6, 3) . '/' . $activity->subclass->icon->disk_name;

                $feature = [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'region_id' => $activity->region_id,
                        'subclass' => [
                            'id' => $activity->subclass->id,
                            'class_id' => $activity->subclass->class_id,
                            'name' => $activity->subclass->name,
                            'icon' => [
                                'id' => $activity->subclass->icon->id,
                                'subclasse_id' => $activity->subclass->icon->subclasse_id,
                                // 'disk_name' => $activity->subclass->icon->disk_name,
                                'file_name' => $activity->subclass->icon->file_name,
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

            return ApiServices::statuscode200(["geojson" => $geojson]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
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
    public function store(StoreRegionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $chaveCache = "IconController_show_" . $id;
            $region = Cache::remember($chaveCache, $this->redisService->getRedisTtlLow(), function () use ($id) {
                return Region::select(
                    'id',
                    'name',
                    'city',
                    DB::raw('ST_AsGeoJSON(geometry) as geometry'),
                    DB::raw('ST_AsGeoJSON(center) as center')
                )
                    ->find($id);
            });

            $feature = [
                "type" => "Feature",
                "geometry" => json_decode($region->geometry),
                "properties" => [
                    "stroke"=> "#ff0000",
                    "stroke-opacity"=> 1,
                    "fill-opacity"=> 0,
                    // "ID" => $region->id,
                    // "Nome" => $region->name,
                    // "Cidade" => $region->city,
                    // "Centro" => json_decode($region->center)
                ]
            ];

            $geojson = [
                "type" => "FeatureCollection",
                "features" => [$feature]
            ];

            return response()->json($geojson);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
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
        try {
            $query = Street::select(
                '*',
                DB::raw('ST_AsGeoJSON(geometry) as geometry')
            )
                ->where('region_id', $id)
                ->has('streetCondition');
            $query2 = Street::select(
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
                $query2->whereIn('street_condition_id', $condition_ids);

            }
            
            $ruas = $query2->select('id as street_id')->get();
            $streetIds = $ruas->pluck('street_id')->toArray();
            $geo = New GeospatialService();
            $totalMetrosRuas = 0;
            
            // Iterar sobre cada ID de rua
            foreach ($streetIds as $street_id) {
                // Criar um novo objeto Request para cada ID de rua
                $newRequest = new Request(['street_id' => $street_id]);

                // Chamar a função getLengthStreet com o novo request
                $response = $geo->getLengthStreet($newRequest);
                
                $length = floatval($response['length']);
                $totalMetrosRuas += $length;    
            }

            $streets = Cache::remember($chaveCache, 1, function () use ($query) {                
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
                        // "stroke" => "#EFE944", // pavimentado
                        "stroke" => $street->color, // agua
                        // "stroke" => '#ffea00', // entulho
                        "stroke-width"=> 2,
                        "stroke-opacity"=> 1
                    ]);

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
            $geojson = [
                "type" => "FeatureCollection",
                "comprimentoTotal" => $totalMetrosRuas,
                "features" => $streets->toArray(),
            ];

            return ApiServices::statuscode200(["geojson" => $geojson]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    public function getStreetsByRegion2(int $id)
    {
        try {
            $streets = Street::select(
                '*',
                DB::raw('ST_AsText(geometry) as geometry')
            )
                ->where('region_id', $id)
                ->has('streetCondition')
                ->get()
                ->map(function ($street) {
                    $decodedProperties = json_decode($street->properties, true);
                    $decodedProperties = $street->properties;
                    $geojson_streets = [
                        "properties" => $decodedProperties
                    ];
                    return $geojson_streets;
                });

            $geojson = [
                "type" => "FeatureCollection",
                "features" => $streets
            ];

            return ApiServices::statuscode200(["geojson" => $geojson]);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
    }

    // http://127.0.0.1:8000/api/v5/geojson/regions/1/activities?page=1&subclasses[]=28
    public function getActivitiesByRegion(int $id, Request $request)
    {
        try {
            // Obtém os IDs de subclasses do parâmetro de query
            $subclasses = $request->query('subclasses', []);
            $name = $request->query('name', ''); // Obtém o nome, se presente

            // Busca as atividades pela região e filtra por subclass_id, com paginação
            $activities = Activitie::where('region_id', $id)
                ->select('*',DB::raw('ST_AsGeoJson(geometry) as geometry'))
                ->when(!empty($subclasses), function ($query) use ($subclasses) {
                    return $query->whereIn('subclass_id', $subclasses);
                })
                ->when(!empty($name), function ($query) use ($name) {
                    // Converte para minúsculas tanto no banco quanto no valor buscado
                    return $query->where(DB::raw('LOWER(name)'), 'like', '%' . strtolower($name) . '%');
                })
                ->with(['subclass', 'region', 'subclass.classe']) // Carrega as relações necessárias
                ->paginate(12); // Pagina os resultados, retornando 5 por página

            // Mapeia os dados paginados para GeoJSON
            $activities->getCollection()->transform(function ($activitie) {
                return [
                    "id" => $activitie->id,
                    "region_id" => $activitie->region_id,
                    "subclass_id" => $activitie->subclass_id,
                    "name" => $activitie->name,
                    "active" => $activitie->active,
                    "geometry" => $activitie->geometry,
                    "level" => $activitie->level,
                    "subclass" => [
                        'id' => $activitie->subclass->id,
                        "class_id" => $activitie->subclass->class_id,
                        "name" => $activitie->subclass->name,
                        "related_color" => $activitie->subclass->related_color,
                        "class" => [
                            'id' => $activitie->subclass->classe->id,
                            'name' => $activitie->subclass->classe->name,
                            'related_color' => $activitie->subclass->classe->related_color,
                        ],
                        "related_icon" => [
                            'id' => $activitie->subclass->related_icon->id,
                            'disk_name' => $activitie->subclass->related_icon->disk_name,
                            'file_name' => $activitie->subclass->related_icon->file_name,
                            'path' => $activitie->subclass->related_icon->getPath(),
                        ],
                    ],
                    // "Centro" => json_decode($region->center)
                ];
            });

            return response()->json($activities);
        } catch (Exception $e) {
            return ApiServices::statuscode500($e->getMessage());
        }
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
