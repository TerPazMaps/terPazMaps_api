<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\ApiServices;
use App\Models\Activitie;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GeospatialService
{
    private $redis_ttl;

    public function __construct()
    {
        $this->redis_ttl = 3600;
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/activities-nearby?region_id=7&subclass_id=28&raio=3000&latitude=-1.465815&longitude=-48.459401
    public function getActivitiesbyArea(Request $request)
    {
        $region_id = $request->input('region_id');
        // $subclass_id = $request->input('subclass_id');
        $subclass_id = array_map('intval', explode(',', $request->input('subclass_id')));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio');

        $startTime = microtime(true);

        $chaveCache = "activitiesbyArea_" . $region_id . "_" . $request->input('subclass_id') . "_" . $raio . "_" . $latitude . "_" . $longitude;
        $query = Cache::remember($chaveCache, $this->redis_ttl, function () use ($region_id, $subclass_id, $latitude, $longitude, $raio) {
            return DB::table('activities')->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                ->where('region_id', $region_id)
                ->whereIn('subclass_id', $subclass_id)
                ->whereRaw("ST_Distance_Sphere(PointFromText('POINT($longitude $latitude)', 4326), geometry) <= $raio")
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

        $buffer = $this->buffer($raio, $request->input('latitude'), $request->input('longitude'));
        // Adicionando as features e o buffer em um único array
        $features = $activities->toArray();
        $features[] = $buffer;

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];

        return ["geojson" => $featureCollection];
    }

    public function getActivitiesbyAreaPG(Request $request)
    {
        $region_id = $request->input('region_id');
        $subclass_id = array_map('intval', explode(',', $request->input('subclass_id')));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio');

        $startTime = microtime(true);
        $chaveCache = "activitiesbyArea_" . $region_id . "_" . $request->input('subclass_id') . "_" . $raio . "_" . $latitude . "_" . $longitude;
        $query = Cache::remember($chaveCache, $this->redis_ttl, function () use ($region_id, $subclass_id, $latitude, $longitude, $raio) {
            return DB::table('activities')
                ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
                ->where('region_id', $region_id)
                ->where('subclass_id', $subclass_id)
                ->whereRaw("ST_DistanceSphere(ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326), geometry) <= $raio")
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

        $buffer = $this->buffer($raio, $request->input('latitude'), $request->input('longitude'));
        // Adicionando as features e o buffer em um único array
        $features = $activities->toArray();
        $features[] = $buffer;

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];

        $geojson = ["geojson" => $featureCollection];

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

    // http://127.0.0.1:8000/api/v5/geojson/services/points-of-interest?region_id=1&referenciaId=7&pontoBuscadoId=28&raio=100
    public function getPointsOfInterest(Request $request)
    {
        $region_id = $request->region_id;
        $referenciaId = $request->referenciaId;
        // $pontoBuscadoId = $request->pontoBuscadoId;
        $pontoBuscadoId = array_map('intval', explode(',', $request->input('pontoBuscadoId')));
        $raio = $request->raio; // Raio em metros para verificar a proximidade

        // Consulta para obter as coordenadas das referencias
        $chaveCache = "getEscolas_referencias_" . $region_id . "_" . $referenciaId;
        $referencias = Cache::remember($chaveCache, $this->redis_ttl, function () use ($region_id, $referenciaId) {
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
            $chaveCache = "getEscolas_pontosProximosClone_" . $longitude . "_" . $latitude . "_" . $raio;
            $pontosProximos = Cache::remember($chaveCache, $this->redis_ttl, function () use ($pontoBuscadoId, $longitude, $latitude, $raio) {
                return DB::table('activities')
                    ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'), DB::raw("ST_Distance_Sphere(ST_GeomFromText('POINT($longitude $latitude)', 4326), geometry) as distance"))
                    ->whereIn('subclass_id', $pontoBuscadoId)
                    ->whereRaw("ST_Distance_Sphere(ST_GeomFromText('POINT($longitude $latitude)', 4326), geometry) <= $raio")
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

                // $buffer = $this->buffer($raio, $latitude, $longitude);
                // $features[] = $buffer;
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

    public function getPointsOfInterestPG(Request $request)
    {
        $region_id = $request->region_id;
        $referenciaId = $request->referenciaId;
        // $pontoBuscadoId = $request->pontoBuscadoId;
        $pontoBuscadoId = array_map('intval', explode(',', $request->input('pontoBuscadoId')));
        $raio = $request->raio; // Raio em metros para verificar a proximidade

        // Consulta para obter as coordenadas das referencias
        $chaveCache = "getEscolas_referencias_" . $region_id . "_" . $referenciaId;
        $referencias = Cache::remember($chaveCache, $this->redis_ttl, function () use ($region_id, $referenciaId) {
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
            $chaveCache = "getEscolas_pontosProximosClone_" . $longitude . "_" . $latitude . "_" . $raio;
            $pontosProximos = Cache::remember($chaveCache, $this->redis_ttl, function () use ($pontoBuscadoId, $longitude, $latitude, $raio) {
                return DB::table('activities')
                    ->select(
                        '*',
                        DB::raw('ST_AsGeoJSON(geometry) as geometry'),
                        // DB::raw("ST_Distance_Sphere(ST_GeomFromText('POINT($longitude $latitude)', 4326), geometry) as distance")
                        DB::raw("ST_DistanceSphere(ST_SetSRID(ST_MakePoint($longitude, $latitude), 4326), geometry) as distance")
                    )
                    ->whereIn('subclass_id', $pontoBuscadoId)
                    // ->whereRaw("ST_Distance_Sphere(ST_GeomFromText('POINT($longitude $latitude)', 4326), geometry) <= $raio")
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

                $buffer = $this->buffer($raio, $latitude, $longitude);
                $features[] = $buffer;
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

    // http://127.0.0.1:8000/api/v5/geojson/services/length-street?street_id=1
    public function getLengthStreet(Request $request)
    {
        $street_id = $request->street_id;
        $chaveCache = "getLengthStreet_" . $street_id;
        $street = Cache::remember($chaveCache, $this->redis_ttl, function () use ($street_id) {
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

    public function getBufferSum(Request $request)
    {
        // Obtenha os parâmetros
        $raio = $request->has('raio') ? array_map('intval', explode(',', $request->raio)) : null;
        // Processar as coordenadas passadas como múltiplos newActivities
        $newActivities = $request->has('newActivities') ? $request->input('newActivities') : null;
        
        // Inicialize um array para armazenar as coordenadas divididas
        $newActivitiesProcessed = [];
        
        // Se existir newActivities, dividir cada string em pares de [lat, lng]
        if ($newActivities) {
            foreach ($newActivities as $activity) {
                // Dividir a string pelo separador de vírgula e converter em float
                $coordinates = array_map('floatval', explode(',', $activity));
                
                // Verificar se temos dois valores após a divisão (lat e lng)
                if (count($coordinates) == 2) {
                    $newActivitiesProcessed[] = $coordinates;
                }
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
        $geometries = Activitie::select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->has('subclass')
            ->has('subclass.related_icon')
            ->where('region_id', $region_id)
            ->where('subclass_id', $subclass)
            ->get();

        // Para cada raio, calculamos os buffers para as atividades no banco e os novos pontos
        foreach ($raio as $rIndex => $r) {
            // Obter buffer das atividades existentes no banco
            $unionBuffer = DB::table('activities')
                ->select(
                    DB::raw('ST_AsGeoJSON(ST_Union(ST_Transform(ST_Buffer(ST_Transform(ST_SetSRID(geometry, ' . $srid_original . '), ' . $srid_metros . '), ' . $r . '), ' . $srid_original . '))) AS buffered_geometry')
                )
                ->where('region_id', $region_id)
                ->where('subclass_id', $subclass)
                ->first();

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
                        'subclass' => 'Proposta', // Ajuste conforme necessário
                        'path' => '', // Adicione um caminho se aplicável
                        'marker' => [ // Propriedades do marcador
                            'marker-color' => '#ff0000',
                            'marker-size' => 'medium',
                            'marker-symbol' => 'construction'
                        ]
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
                        "SELECT ST_AsGeoJSON(ST_Union(ARRAY[
                            ST_GeomFromGeoJSON('$unionBuffer->buffered_geometry'), 
                            ST_GeomFromGeoJSON('{$unionBufferNewPoints[0]->buffered_geometry}')
                        ])) AS buffered_geometry"
                    );
                    
                    // Adicionar a geometria unificada ao array de buffers
                    $buffers[] = [
                        'buffered_geometry' => $finalUnionBuffer[0]->buffered_geometry,
                        'color' => $colors[$rIndex],
                    ];
                }
            }
        }

        // Adicionar os pontos centrais das atividades no banco
        $geometries = $geometries->reverse();
        foreach ($geometries as $geometry) {
            $central_points[] = [
                'id' => $geometry->id,
                'geometry' => $geometry->geometry,
                'name' => $geometry->name,
                'subclass' => $geometry->subclass->name,
                'path' => $geometry->subclass->related_icon->getPath(),
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
                    'subclass' => $point['subclass'],
                    'path' => $point['path'],
                ],
                "geometry" => json_decode($point['geometry'])
            ];

            // Se o ponto tiver um marcador, adicione as propriedades do marcador
            if (isset($point['marker'])) {
                $feature['properties'] = array_merge($feature['properties'], $point['marker']);
            }

            $geojson["features"][] = $feature;
        }

        // Retornar o GeoJSON resultante
        return $geojson;
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/buffer?latitude=-1.34119991436441&longitude=-48.40409132788111
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
        $chaveCache = "buffer_" . $raio . $latitude . $longitude;
        $buffer = Cache::remember($chaveCache, $this->redis_ttl, function () use ($raio, $longitude, $latitude) {

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
}
