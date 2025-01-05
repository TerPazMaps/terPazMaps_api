<?php

namespace App\Services;

use App\Models\Street;
use App\Models\Activitie;
use Illuminate\Http\Request;
use App\Services\ApiServices;
use App\Services\RedisService;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Cache;


class GeospatialService
{
    private $redisService;
    
    public function __construct()
    {
        $this->redisService = new RedisService();
    }

    // funcionalidade Pergunta 11 postgreeSQL
    // http://127.0.0.1:8000/api/v5/geojson/services/activities-nearbyPG?region_id=7&subclass_id=17,30,44,59,75,99,135,145,156,170&raio=1802&latitude=-1.4653&longitude=-48.4616
    public function getActivitiesNearbyPG(Request $request)
    {
        $region_id = $request->input('region_id');
        $subclass_id = array_map('intval', explode(',', $request->input('subclass_id')));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio');

        $chaveCache = $this->redisService->createKeyCacheFromRequest( "getActivitiesNearbyPG",[$region_id, $latitude, $longitude, $raio], $request,['subclass_id']);
       
        $startTime = microtime(true);
        $query = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($region_id, $subclass_id, $longitude, $latitude, $raio) {
            return DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->whereIn('subclass_id', $subclass_id)
            ->whereRaw("ST_DistanceSphere(ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326), geometry) <= $raio")
            ->limit(3000)
            ->get();
        });

        $endTime = microtime(true);
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        $activities = $query->map(function ($activitie) {
            $geometry = json_decode($activitie->geometry);
            $coordinates = $geometry->coordinates;
            $type = $geometry->type;

            $properties = [
                "id" => $activitie->id,
                "region_id" => $activitie->region_id,
                "subclass_id" => $activitie->subclass_id,
                "name" => $activitie->name,
            ];

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

       
        // Obter as outras features e adicionar o buffer como uma feature
        $features = $activities->toArray();
        $features[] = $this->bufferPG($raio,$latitude, $longitude); // Adiciona o buffer corretamente

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];

        return $featureCollection;
    }
    // funcionalidade Pergunta 11 MySQL
    // http://127.0.0.1:8000/api/v5/geojson/services/activities-nearbyMS?region_id=7&subclass_id=28&raio=1500&latitude=-1.465815&longitude=-48.459401
    public function getActivitiesNearbyMS(Request $request)
    {
        $region_id = $request->input('region_id');
        $subclass_id = array_map('intval', explode(',', $request->input('subclass_id')));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio'); // Raio em metros
    
        $startTime = microtime(true);
    
        $chaveCache = $this->redisService->createKeyCacheFromRequest( "getActivitiesNearbyMS_",[$region_id, $latitude, $longitude, $raio], $request,['subclass_id']);

        // Consulta para obter atividades na região e dentro do raio especificado
        $query = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($region_id, $subclass_id, $longitude, $latitude, $raio) {
            return DB::table('activities')
                ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                ->where('region_id', $region_id)
                ->whereIn('subclass_id', $subclass_id)
                ->whereRaw("ST_Distance_Sphere(ST_GeomFromText('POINT($latitude $longitude)', 4326), geometry) <= ?", [$raio])
                ->get();
        });

        $endTime = microtime(true);
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);
    
        // Mapeia as atividades para GeoJSON
        $activities = $query->map(function ($activitie) {
            $geometry = json_decode($activitie->geometry);
            $coordinates = $geometry->coordinates;
            $type = $geometry->type;
    
            $properties = [
                "id" => $activitie->id,
                "region_id" => $activitie->region_id,
                "subclass_id" => $activitie->subclass_id,
                "name" => $activitie->name,
            ];
    
            $feature = [
                "type" => "Feature",
                "geometry" => [
                    "type" => $type,
                    "coordinates" => $coordinates,
                ],
                "properties" => $properties,
            ];
            return $feature;
        });
    
        // Adicionando as activities e o buffer em um único array de features
        $features = $activities->toArray(); // Converte as atividades para array
        $features[] = $this->bufferMS($raio, $latitude, $longitude);; // Adiciona o buffer como última feature
    
        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
    
        return $featureCollection;
    }
    
    // funcionalidade Pergunta 12 postgreeSQL
    // http://127.0.0.1:8000/api/v5/geojson/services/points-of-interestPG?region_id=1&referenciaId=7&pontoBuscadoId=28&raio=100
    public function getPointsOfInterestPG(Request $request)
    {
        $region_id = $request->region_id;
        $referenciaId = $request->referenciaId;
        $pontoBuscadoId = array_map('intval', explode(',', $request->input('pontoBuscadoId')));
        $raio = $request->raio; // Raio em metros para verificar a proximidade
        $chaveCache = $this->redisService->createKeyCacheFromRequest( "getEscolas_referencias",[$region_id, $referenciaId], $request,['pontoBuscadoId', 'raio']);
        // Consulta para obter as coordenadas das referencias
        $referencias = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($region_id, $referenciaId) {
            return DB::table('activities')
                ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                ->where('region_id', $region_id)
                ->where('subclass_id', $referenciaId)
                ->get();
        });

        // Array para armazenar as referencias e pontosBuscados no formato GeoJSON
        $features = [];

        $startTime = microtime(true);

        // Iterar sobre as referencias
        foreach ($referencias as $referencia) {
            $geometry = json_decode($referencia->geometry);
            $coordinates = $geometry->coordinates;
            $latitude = $coordinates[1];
            $longitude = $coordinates[0];

            // Consulta para obter os pontos próximos à referência atual
            $chaveCache = $this->redisService->createKeyCacheFromRequest("getEscolas_pontosProximosClone",[$latitude, $longitude, $raio], $request, []);
            $pontosProximos = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($referencias, $longitude, $latitude, $raio, $pontoBuscadoId) {
                return DB::table('activities')
                    ->select(
                        '*',
                        DB::raw('ST_AsGeoJSON(geometry) as geometry'),
                        DB::raw("ST_DistanceSphere(ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326), geometry) as distance")
                    )
                    ->whereIn('subclass_id', $pontoBuscadoId)
                    ->whereRaw("ST_DistanceSphere(ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326), geometry) <= $raio")
                    ->get();
            });
            // Se houver pontos próximos, adiciona a referência e suas escolas ao resultado
            if ($pontosProximos->count() > 0) {
                // Adiciona a referência ao array de features
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'id' => $referencia->id,
                        'region_id' => $referencia->region_id,
                        'subclass_id' => $referencia->subclass_id,
                        'name' => $referencia->name,
                        'marker-color' => '#FF0000',

                    ]
                ];

                // Adiciona os pontos próximos da referência ao array de features
                foreach ($pontosProximos as $ponto) {
                    $geometryPonto = json_decode($ponto->geometry);
                    $features[] = [
                        'type' => 'Feature',
                        'geometry' => $geometryPonto,
                        'properties' => [
                            'id' => $ponto->id,
                            'region_id' => $ponto->region_id,
                            'subclass_id' => $ponto->subclass_id,
                            'name' => $ponto->name,
                        ]
                    ];
                }

                $features[] = $this->bufferPG($raio,$latitude, $longitude);
            }
        }

        $endTime = microtime(true);        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
        return $geojson;
    }
    // funcionalidade Pergunta 12 MySQL
    // http://127.0.0.1:8000/api/v5/geojson/services/points-of-interestMS?region_id=1&referenciaId=7&pontoBuscadoId=28&raio=100
    public function getPointsOfInterestMS(Request $request)
    {
        $region_id = $request->region_id;
        $referenciaId = $request->referenciaId;
        $pontoBuscadoId = array_map('intval', explode(',', $request->input('pontoBuscadoId')));
        $raio = $request->raio; // Raio em metros para verificar a proximidade

        // Consulta para obter as coordenadas das referencias
        $chaveCache = $this->redisService->createKeyCacheFromRequest("getEscolas_referencias",[$region_id, $referenciaId], $request, []);
        $referencias = Cache::remember($chaveCache, $this->redisService->getRedisTtl(),function() use ($region_id, $referenciaId){
            return DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $referenciaId)
            ->get();
        });
                
        // Array para armazenar as referencias e pontosBuscados no formato GeoJSON
        $features = [];

        $startTime = microtime(true);

        // Iterar sobre as referencias
        foreach ($referencias as $referencia) {
            $geometry = json_decode($referencia->geometry);
            $coordinates = $geometry->coordinates;
            $latitude = $coordinates[1];
            $longitude = $coordinates[0];

            // Consulta para obter os pontos próximos à referência atual
            $chaveCache = $this->redisService->createKeyCacheFromRequest("getEscolas_pontosProximosClone",[$latitude, $longitude, $raio], $request, []);
            $pontosProximos = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function() use ($pontoBuscadoId, $longitude, $latitude, $raio){
                return DB::table('activities')
                    ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'), 
                    DB::raw("ST_Distance_Sphere(ST_GeomFromText('POINT($latitude $longitude)', 4326), geometry) as distance")
                    )
                    ->whereIn('subclass_id', $pontoBuscadoId)
                    ->whereRaw("ST_Distance_Sphere(ST_GeomFromText('POINT($latitude $longitude)', 4326), geometry) <= $raio")

                    ->get();
            });
            // dd($pontosProximos, $latitude.' '. $longitude);

            // Se houver pontos próximos, adiciona a referência e suas escolas ao resultado
            if ($pontosProximos->count() > 0) {
                // Adiciona a referência ao array de features
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'id' => $referencia->id,
                        'region_id' => $referencia->region_id,
                        'subclass_id' => $referencia->subclass_id,
                        'name' => $referencia->name,
                        'marker-color' => '#FF0000',

                    ]
                ];

                // Adiciona os pontos próximos da referência ao array de features
                foreach ($pontosProximos as $ponto) {
                    $geometryPonto = json_decode($ponto->geometry);
                    $features[] = [
                        'type' => 'Feature',
                        'geometry' => $geometryPonto,
                        'properties' => [
                            'id' => $ponto->id,
                            'region_id' => $ponto->region_id,
                            'subclass_id' => $ponto->subclass_id,
                            'name' => $ponto->name,
                        ]
                    ];
                }

                $features[] = $this->bufferMS($raio, $latitude, $longitude);  
            }
        }

        $endTime = microtime(true);        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
        return $geojson;
    }

    // funcionalidade Pergunta 14 postgreeSQL
    // http://127.0.0.1:8000/api/v5/geojson/services/difficult-access-activitiesPG?region_id=1&subclass[]=28
    public function getDifficultAccessActivitiesPG(Request $request) 
    {
        $region_id = $request->region_id;
        $subclass = $request->has('subclass') ? $request->input('subclass') : null;
        $condition = $request->has('condition') ? $request->input('condition') : [1,2,3,4,5,6,7];
        $request['condition']=[1,2,3,4,5,6,7];
        $distance = 10; // distância em metros da atividade em relação a rua.
        
        $chaveCache = $this->redisService->createKeyCacheFromRequest("getDifficultAccessActivitiesPG",[$region_id], $request,['subclass', 'condition']);
        $atividades = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($region_id, $distance, $subclass, $condition) {
            return DB::table('streets')
                ->select(
                    'streets.id as street_id',
                    'activities.id as activity_id',
                    'activities.name',
                    'activities.region_id',
                    'activities.subclass_id',
                    DB::raw('ST_AsGeoJSON(streets.geometry) as street_geometry'),
                    DB::raw('ST_AsGeoJSON(activities.geometry) as activity_geometry')
                )
                ->join('activities', function ($join) use ($distance) {
                    $join->on(DB::raw('ST_DWithin(ST_Transform(streets.geometry, 3857), ST_Transform(activities.geometry, 3857), '.$distance.')'), DB::raw('TRUE'));
                })
                ->where('streets.region_id', $region_id)
                ->whereIn('streets.street_condition_id', $condition)
                ->when($subclass, function ($query, $subclass) {
                    return $query->whereIn('activities.subclass_id', $subclass);
                })
                ->distinct()
                ->get();
        });
    
        // Mapear as atividades e ruas para o formato GeoJSON
        $features = $atividades->map(function ($record) {
            $activityGeometry = json_decode($record->activity_geometry);
            $streetGeometry = json_decode($record->street_geometry);
    
            // Feature para a atividade
            $activityFeature = [
                "type" => "Feature",
                "geometry" => [
                    "type" => $activityGeometry->type,
                    "coordinates" => $activityGeometry->coordinates
                ],
                "properties" => [
                    "id" => $record->activity_id,
                    "region_id" => $record->region_id,
                    "subclass_id" => $record->subclass_id,
                    "name" => $record->name,
                    "type" => "activity"
                ],
            ];
    
            // Feature para a rua - isso aqui é informação adicional
            // $streetFeature = [
            //     "type" => "Feature",
            //     "geometry" => [
            //         "type" => $streetGeometry->type,
            //         "coordinates" => $streetGeometry->coordinates
            //     ],
            //     "properties" => [
            //         "id" => $record->street_id,
            //         "type" => "street"
            //     ],
            // ];
    
            return [$activityFeature];
        })->flatten(1)->unique('properties.id')->values()->toArray();
    
        // Criação do FeatureCollection GeoJSON
        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        
        return $featureCollection;
    }
    // funcionalidade Pergunta 14 MySQL
    // http://127.0.0.1:8000/api/v5/geojson/services/difficult-access-activitiesMS?region_id=1&subclass[]=28
    public function getDifficultAccessActivitiesMS(Request $request)
    {
        $distance = 10; // distância em metros
        $region_id = $request->region_id;
        $subclass = $request->has('subclass') ? $request->input('subclass') : null;
        $condition = $request->has('condition') ? $request->input('condition') : [1,2,3,4,5,6,7];
        $request['condition']= $condition;

        $chaveCache = $this->redisService->createKeyCacheFromRequest("getDifficultAccessActivitiesMS" ,[$region_id],$request,['subclass', 'condition']);
        $atividades = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function() use ($region_id, $subclass, $condition, $distance){ 
            return DB::table('streets')
                ->select(
                    'streets.id as street_id',
                    'activities.id as activity_id',
                    'activities.name',
                    'activities.region_id',
                    'activities.subclass_id',
                    // DB::raw('ST_AsGeoJSON(streets.geometry) as street_geometry'),
                    DB::raw('ST_AsGeoJSON(activities.geometry) as activity_geometry')
                )
                ->join('activities', 'streets.region_id', '=', 'activities.region_id') // Ajuste conforme as chaves necessárias
                ->where('streets.region_id', $region_id)
                ->whereIn('activities.subclass_id', $subclass)
                ->whereRaw('ST_Distance(ST_Transform(streets.geometry, 3857), ST_Transform(activities.geometry, 3857)) < ?', [$distance])
                ->whereIn('streets.street_condition_id', $condition)
                ->distinct()
                ->get();
            });

            // dd($atividades);

        // Mapear as atividades e ruas para o formato GeoJSON
        $features = collect($atividades)->map(function ($record) {
            $activityGeometry = json_decode($record->activity_geometry);
            // $streetGeometry = json_decode($record->street_geometry);

            // Feature para a atividade
            $activityFeature = [
                "type" => "Feature",
                "geometry" => [
                    "type" => $activityGeometry->type,
                    "coordinates" => $activityGeometry->coordinates
                ],
                "properties" => [
                    "id" => $record->activity_id,
                    "region_id" => $record->region_id,
                    "subclass_id" => $record->subclass_id,
                    "name" => $record->name,
                    "type" => "activity"
                ],
            ];

            // Feature para a rua
            // $streetFeature = [
            //     "type" => "Feature",
            //     "geometry" => [
            //         "type" => $streetGeometry->type,
            //         "coordinates" => $streetGeometry->coordinates
            //     ],
            //     "properties" => [
            //         "id" => $record->street_id,
            //         "type" => "street"
            //     ],
            // ];

            return [$activityFeature,];
        })->flatten(1)->unique('properties.id')->values()->toArray();

        // Criação do FeatureCollection GeoJSON
        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        
        return $featureCollection;
    }

    // funcionalidade Pergunta 15 postgreeSQL
    // http://127.0.0.1:8000/api/v5/geojson/services/bufferSumPG?region_id=1&subclass=29&raio=100,200,300,400&newActivities[]=-1.3230,%20-48.4019
    public function getBufferSumPG(Request $request)
    {
        // Obtenha os parâmetros
        $raio = $request->has('raio') ? array_map('intval', explode(',', $request->raio)) : null;
        $newActivities = $request->has('newActivities') ? $request->input('newActivities') : null;

        // Inicialize um array para armazenar as coordenadas divididas
        $newActivitiesProcessed = [];

        // Se existir newActivities, dividir cada string em pares de [lat, lng]
        if ($newActivities) {
            foreach ($newActivities as $activity) {
                $coordinates = array_map('floatval', explode(',', $activity));
                $newActivitiesProcessed[] = $coordinates;
            }
        }

        $region_id = $request->region_id;
        $subclass = $request->subclass;

        // SRIDs
        $srid_original = 4326; // WGS 84
        $srid_metros = 3857; // Web Mercator

        // Cores para os buffers
        $colors = ['#00ff00', '#4cc0bf', '#FFFF00', '#ff0000'];
        $raio = array_slice($raio, 0, 4); // Limitando os raios a 4

        // Inicializar buffers e pontos centrais
        $buffers = [];
        $central_points = [];

        // Obter as geometrias das atividades existentes
        $chaveCache = $this->redisService->createKeyCacheFromRequest("getBufferSumPG" ,[$region_id, $subclass],$request,['newActivities']);
        $geometries = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($region_id, $subclass) {
            return Activitie::select('name', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                // ->has('subclass')
                // ->has('subclass.related_icon')
                ->where('region_id', $region_id)
                ->where('subclass_id', $subclass)
                ->get();
        });

        // Para cada raio, calculamos os buffers para as atividades no banco e os novos pontos
        foreach ($raio as $rIndex => $r) {
            foreach($geometries as $pontos) {
                $lat = json_decode($pontos->geometry)->coordinates[1];
                $lng = json_decode($pontos->geometry)->coordinates[0];
                $points[] = "ST_SetSRID(ST_Point($lng, $lat), $srid_original)";
            }
            $pointsSQL = implode(',', $points);
            $unionBuffer = DB::select(
                "SELECT ST_AsGeoJSON(ST_Union(ST_Transform(ST_Buffer(ST_Transform(geom, $srid_metros), $r), $srid_original))) AS buffered_geometry
                FROM (SELECT unnest(ARRAY[$pointsSQL]) AS geom) AS temp"
            );
            // dd($unionBuffer[0]->buffered_geometry);

            // Inicialize um array para armazenar todas as geometrias de novos pontos
            $points = [];
            if (!empty($newActivitiesProcessed)) {
                foreach ($newActivitiesProcessed as $point) {
                    $lat = $point[0];
                    $lng = $point[1];

                    // Criar uma geometria para o ponto (ST_GeomFromText)
                    $points[] = "ST_SetSRID(ST_Point($lng, $lat), $srid_original)";

                    // Adicionar o ponto central correspondente
                    $central_points[] = [
                        'geometry' => json_encode(["type" => "Point", "coordinates" => [$lng, $lat]]),
                        'name' => "Nova Construção ($lat, $lng)", // Nome do ponto
                        // 'subclass' => 'Proposta', // Ajuste conforme necessário
                        // 'path' => '', // Adicione um caminho se aplicável
                        // 'marker' => [ // Propriedades do marcador
                        //     'marker-color' => '#ff0000',
                        //     'marker-size' => 'medium',
                        //     'marker-symbol' => 'construction'
                        // ]
                    ];
                }

                // Combinar as geometrias em uma string que pode ser usada no SQL
                $pointsSQL = implode(',', $points);

                // Calcular o buffer e a união para esses pontos usando a mesma lógica do banco
                $unionBufferNewPoints = DB::select(
                    "SELECT ST_AsGeoJSON(ST_Union(ST_Transform(ST_Buffer(ST_Transform(geom, $srid_metros), $r), $srid_original))) AS buffered_geometry
                    FROM (SELECT unnest(ARRAY[$pointsSQL]) AS geom) AS temp"
                );

                // Se ambas as geometrias existirem, vamos uni-las com ST_Union
                if ($unionBuffer && !empty($unionBufferNewPoints)) {
                    // Unir geometrias do banco de dados e os novos pontos
                    $finalUnionBuffer = DB::select(
                        "SELECT ST_AsGeoJSON(ST_Union(ARRAY[ST_GeomFromGeoJSON('{$unionBuffer[0]->buffered_geometry}'),ST_GeomFromGeoJSON('{$unionBufferNewPoints[0]->buffered_geometry}')])) AS buffered_geometry"
                    );

                    // Adicionar a geometria unificada ao array de buffers
                    $buffers[] = [
                        'buffered_geometry' => $finalUnionBuffer[0]->buffered_geometry,
                        'color' => $colors[$rIndex],
                    ];
                } elseif ($unionBuffer) {
                    // Apenas buffer das atividades do banco de dados
                    $buffers[] = [
                        'buffered_geometry' => $unionBuffer[0]->buffered_geometry,
                        'color' => $colors[$rIndex],
                    ];
                }
            } else {
                // Apenas buffer das atividades do banco de dados
                $buffers[] = [
                    'buffered_geometry' => $unionBuffer[0]->buffered_geometry,
                    'color' => $colors[$rIndex],
                ];
            }
        }

        // Adicionar os pontos centrais das atividades no banco
        foreach ($geometries->reverse() as $geometry) {
            $central_points[] = [
                // 'id' => $geometry->id,
                'geometry' => $geometry->geometry,
                'name' => $geometry->name,
                // 'subclass' => $geometry->subclass->name,
                // 'path' => $geometry->subclass->related_icon->getPath(),
            ];
        }

        // Formatar resposta GeoJSON
        $geojson = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        // Adicionar buffers ao GeoJSON
        foreach (array_reverse($buffers) as $buffer) {
            $geojson["features"][] = [
                "type" => "Feature",
                "properties" => [
                    "stroke" => $buffer['color'],
                    "stroke-width" => 2,
                    "stroke-opacity" => 1,
                    "fill" => $buffer['color'],
                    "fill-opacity" => 0.2
                ],
                "geometry" => json_decode($buffer['buffered_geometry'])
            ];
        }

        // Adicionar os pontos originais e novos ao GeoJSON
        foreach ($central_points as $point) {
            $feature = [
                "type" => "Feature",
                "properties" => [
                    'name' => $point['name'],
                    // 'subclass' => $point['subclass'],
                    // 'path' => $point['path'],
                ],
                "geometry" => json_decode($point['geometry'])
            ];

            // Se o ponto tiver um marcador, adicione as propriedades do marcador
            if (isset($point['marker'])) {
                $feature['properties'] = array_merge($feature['properties']);
            }

            $geojson["features"][] = $feature;
        }

        // Retornar o GeoJSON resultante
        return $geojson;
    }
    // funcionalidade Pergunta 15 MySQL
    // http://127.0.0.1:8000/api/v5/geojson/services/bufferSumMS/1/29?region_id=1&subclass=29&raio=100,200,300,400&newActivities[]=-1.3230,%20-48.4019
    public function getBufferSumMS(Request $request)
    {
        $newActivities = $request->has('newActivities') ? $request->input('newActivities') : null;
        // $raio =          $request->has('raio')          ? array_map('intval', explode(',', $request->raio)) : null;
        $raios =          $request->has('raio')          ? $request->input('raio') : null;
        // Inicialize um array para armazenar as coordenadas divididas
        $newActivitiesProcessed = [];

        // Se existir newActivities, dividir cada string em pares de [lat, lng]
        if ($newActivities) {
            foreach ($newActivities as $activity) {
                $coordinates = array_map('floatval', explode(',', $activity));
                $newActivitiesProcessed[] = $coordinates;
            }
        }

        $region_id = $request->region_id;
        $subclass = $request->subclass;

        // Cores para os buffers
        $colors = ['#00ff00', '#4cc0bf', '#FFFF00', '#ff0000'];

        // Inicializar buffers e pontos centrais
        $buffers = [];
        $central_points = [];
        // Obter as geometrias das atividades existentes
        $chaveCache = $this->redisService->createKeyCacheFromRequest("getBufferSumMS" ,[$region_id, $subclass],$request,['newActivities', 'raio']);
        $geometries = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function() use ($region_id, $subclass){
            return Activitie::select('name', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                // ->has('subclass')
                // ->has('subclass.related_icon')
                ->where('region_id', $region_id)
                ->where('subclass_id', $subclass)
                ->get();
        });
        foreach ($raios as $rIndex => $raio) {
            $bufferedGeometries = Activitie::select(DB::raw('ST_AsGeoJSON(ST_Buffer(geometry, ' . $raio . ')) AS buffered_geometry'))
                    ->where('region_id', $region_id)
                    ->where('subclass_id', $subclass)
                    ->get();
            $unionGeometry = null;

            foreach ($bufferedGeometries as $index => $geometry) {
                $currentGeometrySQL = "ST_GeomFromGeoJSON('{$geometry->buffered_geometry}')";

                if (is_null($unionGeometry)) {
                    $unionGeometry = $currentGeometrySQL;
                } else {
                    $unionGeometry = "ST_Union($unionGeometry, $currentGeometrySQL)";
                }
            }
            // Se tiver ao menos uma geometria para unir
            if ($bufferedGeometries->isNotEmpty()) {
                // Inicializa a geometria unida com a primeira
                $unionGeometry = "ST_GeomFromGeoJSON('{$bufferedGeometries[0]->buffered_geometry}')";

                // Realiza as uniões de forma incremental, par a par
                for ($i = 1; $i < count($bufferedGeometries); $i++) {
                    $currentGeometry = "ST_GeomFromGeoJSON('{$bufferedGeometries[$i]->buffered_geometry}')";
                    
                    // Une a geometria atual com a acumulada
                    $unionGeometry = DB::selectOne("SELECT ST_AsGeoJSON(ST_Union($unionGeometry, $currentGeometry)) AS unioned_geometry")->unioned_geometry;
                    
                    // Prepara para a próxima união
                    $unionGeometry = "ST_GeomFromGeoJSON('{$unionGeometry}')";
                }
                $unionGeometry = DB::select("SELECT ST_AsGeoJSON($unionGeometry) AS unioned_geometry");
            }

            // dd($unionGeometry);

            if (!empty($newActivitiesProcessed)) {
                $buffered_geometries = [];

                // Gere a geometria e o buffer de cada ponto
                foreach ($newActivitiesProcessed as $point) {
                    $lat = $point[0];
                    $lng = $point[1];

                    $bufferResult = DB::select("
                        SELECT ST_AsGeoJSON(ST_Buffer(ST_GeomFromText('POINT($lat $lng)', 4326), $raio)) as buffer
                    ");

                    $buffered_geometries[] = $bufferResult[0]->buffer;
                }

                $uniaoBufferPontosNovos = null;
                foreach ($buffered_geometries as $index => $geometry) {
                    $currentGeometrySQL = "ST_GeomFromGeoJSON('{$geometry}')";

                    if (is_null($uniaoBufferPontosNovos)) {
                        $uniaoBufferPontosNovos = $currentGeometrySQL;
                    } else {
                        $uniaoBufferPontosNovos = "ST_Union($uniaoBufferPontosNovos, $currentGeometrySQL)";
                    }
                }
                $uniaoBufferPontosNovos = DB::select("
                    SELECT ST_AsGeoJSON($uniaoBufferPontosNovos) AS unioned_geometry
                ");

                // Se ambas as geometrias existirem, vamos uni-las com ST_Union
                if ($unionGeometry && !empty($uniaoBufferPontosNovos)) {
                    $unionBancoENovos = null;

                    $geometry = "ST_GeomFromGeoJSON('{$unionGeometry[0]->unioned_geometry}')";
                    $geometry2 = "ST_GeomFromGeoJSON('{$uniaoBufferPontosNovos[0]->unioned_geometry}')";

                    $unionBancoENovos = DB::select("SELECT ST_AsGeoJSON(ST_Union($geometry, $geometry2)) AS uniao_final");

                    // Adicionar a geometria unificada ao array de buffers
                    $buffers[] = [
                        'buffered_geometry' => $unionBancoENovos[0]->uniao_final,
                        'color' => $colors[$rIndex],
                    ];
                } elseif ($unionGeometry) {
                    // Apenas buffer das atividades do banco de dados
                    $buffers[] = [
                        'buffered_geometry' => $unionGeometry[0]->buffered_geometry,
                        'color' => $colors[$rIndex],
                    ];
                }
            } else {
                // Apenas buffer das atividades do banco de dados
                $buffers[] = [
                    'buffered_geometry' => $unionGeometry[0]->unioned_geometry,
                    'color' => $colors[$rIndex],
                ];
            }
        }

        // Adicionar os pontos centrais das atividades no banco
        foreach ($geometries->reverse() as $geometry) {
            $central_points[] = [
                // 'id' => $geometry->id,
                'geometry' => $geometry->geometry,
                'name' => $geometry->name,
                // 'subclass' => $geometry->subclass->name,
                // 'path' => $geometry->subclass->related_icon->getPath(),
            ];
        }

        // Formatar resposta GeoJSON
        $geojson = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        // Adicionar buffers ao GeoJSON
        foreach (array_reverse($buffers) as $buffer) {
            $geojson["features"][] = [
                "type" => "Feature",
                "properties" => [
                    "stroke" => $buffer['color'],
                    "stroke-width" => 2,
                    "stroke-opacity" => 1,
                    "fill" => $buffer['color'],
                    "fill-opacity" => 0.2
                ],
                "geometry" => json_decode($buffer['buffered_geometry'])
            ];
        }

        // Adicionar os pontos originais e novos ao GeoJSON
        foreach ($central_points as $point) {
            $feature = [
                "type" => "Feature",
                "properties" => [
                    'name' => $point['name'],
                    // 'subclass' => $point['subclass'],
                    // 'path' => $point['path'],
                ],
                "geometry" => json_decode($point['geometry'])
            ];

            $geojson["features"][] = $feature;
        }
        foreach ($newActivitiesProcessed as $point) {
            $central_points2[] = [
                'geometry' => json_encode(["type" => "Point", "coordinates" => [$point[1], $point[0]]]),
                'name' => "Nova Construção ($point[1], $point[0])",
                // 'subclass' => 'Proposta',
                // 'marker-color' => '#ff0000',
                // 'marker-size' => 'medium',
                // 'marker-symbol' => 'construction'
            ];
        }
        foreach ($central_points2 as $point) {
            $feature = [
                "type" => "Feature",
                "properties" => [
                    'name' => $point['name'],
                    // 'subclass' => $point['subclass'],
                    // 'marker-color' => $point['marker-color'],
                    // 'marker-size' => $point['marker-size'],
                    // 'marker-symbol' => $point['marker-symbol'],
                ],
                "geometry" => json_decode($point['geometry'])
            ];

            $geojson["features"][] = $feature;
        }

        // Retornar o GeoJSON resultante
        return $geojson;
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/distance?lat=-1.34538115355059&lon=-48.4045690844909&lat2=-1.34519276971018&lon2=-48.4041343555742 
    public function getDistance(Request $request)
    {
        $lat1 = $request->lat;
        $lon1 = $request->lon;
        $lat2 = $request->lat2;
        $lon2 = $request->lon2;

        $formattedDistance = number_format($this->calculateDistance($lat1, $lon1, $lat2, $lon2), 1) . " metros";

        return ["distance" => $formattedDistance];
    }

    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6378000; // raio da Terra em metros

        // Convertendo graus para radianos
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        // Calculando diferenças de coordenadas
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        // Aplicando a fórmula de Haversine
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/length-street?street_id=1
    public function getLengthStreet(Request $request)
    {
        $street_id = $request->street_id;
        $chaveCache = $this->redisService->createKeyCacheFromRequest("getLengthStreet" ,[$street_id],$request,[]);
        $street = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($street_id) {
            return DB::table('streets')
                ->select(DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                ->where('id', $street_id)
                ->first();
        });

        $linestring = json_decode($street->geometry);

        if ($linestring->type === 'Polygon') {
            $coordinates = $linestring->coordinates[0]; // Obtém as coordenadas do anel exterior

            $totalLength = 0;

            for ($i = 0; $i < count($coordinates) - 1; $i++) {
                $lon1 = $coordinates[$i][0];
                $lat1 = $coordinates[$i][1];
                $lon2 = $coordinates[$i + 1][0];
                $lat2 = $coordinates[$i + 1][1];

                $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);

                $totalLength += $distance;
            }

            $totalLength = number_format($totalLength / 2, 2);
            return [
                "length" => $totalLength,
                "unit" => "metros",
                "type" => "Polygon",
            ];
        }

        $totalDistance = 0;
        for ($i = 1; $i < count($linestring->coordinates); $i++) {
            $coordinates1 = $linestring->coordinates[$i - 1];
            $coordinates2 = $linestring->coordinates[$i];

            $distanceQuery = $this->calculateDistance($coordinates1[0], $coordinates1[1], $coordinates2[0], $coordinates2[1]);

            $totalDistance += $distanceQuery;
        }

        $formattedLengthMeters = number_format($totalDistance, 2);

        return [
            "length" => $formattedLengthMeters,
            "unit" => "metros",
            "type" => "Linestring"
        ];
    }


    public function bufferPG($raio, $latitude, $longitude)
    {
        $chaveCache = $this->redisService->createKeyCacheFromRequest("bufferPG" ,[$raio, $latitude, $longitude], null,[]);
        $bufferPG = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($longitude, $latitude, $raio) {
            return DB::selectOne("
                SELECT ST_AsGeoJSON(
                    ST_Transform(
                        ST_Buffer(
                            ST_Transform(
                                ST_SetSRID(ST_Point(?, ?), 4326), 3857
                            ), ?
                        ), 4326
                    )
                ) AS geojson
                ", [$longitude, $latitude, $raio]);
        });
        // Decodificar o GeoJSON do buffer para array PHP
        $bufferGeojson = json_decode($bufferPG->geojson, true);

        // Envolver o buffer como uma Feature para adicionar ao FeatureCollection
        $bufferFeature = [
            "type" => "Feature",
            "geometry" => $bufferGeojson,
            "properties" => null,
        ];
        return $bufferFeature;
    }

    public function bufferMS($raio, $latitude, $longitude)
    {
       // Criar o buffer no MySQL
       $chaveCache = $this->redisService->createKeyCacheFromRequest("bufferMS" ,[$raio, $latitude, $longitude], null,[]);
       $buffer = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($longitude, $latitude, $raio) {
            return DB::selectOne("SELECT ST_AsGeoJSON(ST_Buffer(ST_GeomFromText('POINT($latitude $longitude)', 4326), ?)) as buffer", [$raio]);
       });

        $bufferGeojson = json_decode($buffer->buffer, true);
        $bufferFeature = [
            "type" => "Feature",
            "geometry" => $bufferGeojson,
            "properties" => null,
        ];
        return $bufferFeature;
    }

    public static function GeoJsonValidator($data)
    {
        $json = json_decode($data);

        // Verificar se o JSON é válido
        if ($json === null) {
            return ['type' => 'InvalidJSON', 'message' => 'O JSON fornecido é inválido.'];
        }

        // Verificar se é um FeatureCollection
        if (!isset($json->type) || $json->type !== 'FeatureCollection') {
            return ['type' => 'InvalidFeatureCollection', 'message' => 'O JSON deve ser um FeatureCollection.'];
        }

        // Verificar se há features
        if (!isset($json->features) || !is_array($json->features)) {
            return ['type' => 'MissingFeatures', 'message' => 'O JSON deve conter uma lista de features.'];
        }

        // Validar cada feature
        foreach ($json->features as $feature) {
            // Verificar se é uma feature válida
            if (
                !isset($feature->type) || $feature->type !== 'Feature' ||
                !isset($feature->geometry) || !isset($feature->properties)
            ) {
                return ['type' => 'InvalidFeature', 'message' => 'Cada feature deve ter um tipo e uma geometria válida.'];
            }

            // Verificar o tipo de geometria (apenas suportando Polygon)
            if (!isset($feature->geometry->type) || $feature->geometry->type !== 'Polygon') {
                return ['type' => 'InvalidGeometry', 'message' => 'A geometria de cada feature deve ser um polígono.'];
            }

            // Verificar se as coordenadas são um array
            if (!isset($feature->geometry->coordinates) || !is_array($feature->geometry->coordinates)) {
                return ['type' => 'InvalidCoordinates', 'message' => 'As coordenadas de cada feature devem ser um array.'];
            }

            // Verificar se as coordenadas são válidas (um array de arrays de arrays de números)
            foreach ($feature->geometry->coordinates as $coordinates) {
                if (!is_array($coordinates)) {
                    return ['type' => 'InvalidCoordinatesFormat', 'message' => 'As coordenadas de cada feature devem ser uma lista de pontos.'];
                }
                foreach ($coordinates as $point) {
                    if (
                        !is_array($point) || count($point) !== 2 ||
                        !is_numeric($point[0]) || !is_numeric($point[1])
                    ) {
                        return ['type' => 'InvalidPointFormat', 'message' => 'Cada ponto deve ser um par de números.'];
                    }
                }
                // Verificar se o primeiro ponto é igual ao último ponto (forma fechada)
                $firstPoint = $coordinates[0];
                $lastPoint = end($coordinates);
                if ($firstPoint !== $lastPoint) {
                    return ['type' => 'UnclosedPolygon', 'message' => 'O primeiro e o último ponto de cada polígono devem ser iguais (forma fechada).'];
                }
            }

            // Verificar se as propriedades são um objeto
            if (!is_object($feature->properties)) {
                return ['type' => 'InvalidProperties', 'message' => 'As propriedades de cada feature devem ser um objeto.'];
            }
        }

        return true;
    }

    public static function GeoJsonValidatorActivitie($data)
    {
        $json = json_decode($data);

        // Verificar se o JSON é válido
        if ($json === null) {
            return ['type' => 'InvalidJSON', 'message' => 'O JSON fornecido é inválido.'];
        }

        // Verificar se é um FeatureCollection
        if (!isset($json->type) || $json->type !== 'FeatureCollection') {
            return ['type' => 'InvalidFeatureCollection', 'message' => 'O JSON deve ser um FeatureCollection.'];
        }

        // Verificar se há features
        if (!isset($json->features) || !is_array($json->features)) {
            return ['type' => 'MissingFeatures', 'message' => 'O JSON deve conter uma lista de features.'];
        }

        // Validar cada feature
        foreach ($json->features as $feature) {
            // Verificar se é uma feature válida
            if (
                !isset($feature->type) || $feature->type !== 'Feature' ||
                !isset($feature->geometry) || !isset($feature->properties)
            ) {
                return ['type' => 'InvalidFeature', 'message' => 'Cada feature deve ter um tipo e uma geometria válida.'];
            }

            // Verificar o tipo de geometria (apenas suportando point)
            if (!isset($feature->geometry->type) || $feature->geometry->type !== 'Point') {
                return ['type' => 'InvalidGeometry', 'message' => 'A geometria deve ser um Point.'];
            }

            // Verificar se as coordenadas são um array
            if (!isset($feature->geometry->coordinates) || !is_array($feature->geometry->coordinates)) {
                return ['type' => 'InvalidCoordinates', 'message' => 'As coordenadas de cada feature devem ser um array.'];
            }

            // Verificar se as coordenadas são válidas (um array de arrays de arrays de números)
            $point = $feature->geometry->coordinates;
            if (
                !is_array($point) || count($point) !== 2 ||
                !is_numeric($point[0]) || !is_numeric($point[1])
            ) {
                return ['type' => 'InvalidPointFormat', 'message' => 'Cada ponto deve ser um par de números.'];
            }

            // Verificar se as propriedades são um objeto
            if (!is_object($feature->properties)) {
                return ['type' => 'InvalidProperties', 'message' => 'As propriedades de cada feature devem ser um objeto.'];
            }
        }

        return true;
    }

    // Os dois proximos metodos NÃO usão o banco de dados para calcular buffer 
    // já temos função para postgre e mysql, talvez esse deva ser apagado. @TODO

    //http://127.0.0.1:8000/api/v5/geojson/services/buffer?latitude=-1.34119991436441&longitude=-48.40409132788111
    public function getBuffer(Request $request)
    {
        $raio = $request->has('raio') ? intval($request->raio) : null;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        if ($raio < 6) {
            return ApiServices::statuscode422("O raio deve ser maior ou igual a 6 metros.");
        }

        $buffer = $this->buffer($raio, $latitude, $longitude);

        return $buffer;
    }

    public function buffer($raio, $latitude, $longitude)
    {
        $chaveCache = $this->redisService->createKeyCacheFromRequest("bufferMS" ,[$raio, $latitude, $longitude], null,[]);
        $buffer = Cache::remember($chaveCache, $this->redisService->getRedisTtl(), function () use ($raio, $longitude, $latitude) {

            // Calcula o raio em graus decimais
            $raio_graus = round($raio / (111320 * cos(deg2rad($latitude))), 4);
            $num_segmentos = 125;

            // Calcula os pontos do círculo
            $pontos = [];
            for ($i = 0; $i <= $num_segmentos; $i++) {
                $angulo = 2 * pi() * $i / $num_segmentos;
                $ponto_longitude = $longitude + $raio_graus * cos($angulo);
                $ponto_latitude = $latitude + $raio_graus * sin($angulo);
                $pontos[] = [$ponto_longitude, $ponto_latitude];
            }

            // Ajusta a resposta JSON
            $response = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [$pontos]
                ],
                'properties' => [
                    'raio' => $raio
                ]
            ];

            return $response;
        });
        // Retorna a resposta JSON
        return $buffer;
    }
}
