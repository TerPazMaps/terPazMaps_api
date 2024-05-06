<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;

class ServicesController extends Controller
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

        return response()->json($featureCollection, 200);
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/distance?lat=-1.34538115355059&lon=-48.4045690844909&lat2=-1.34519276971018&lon2=-48.4041343555742 
    public function getDistance(Request $request)
    {
        $startTime = microtime(true);
        $lat1 = $request->lat;
        $lon1 = $request->lon;
        $lat2 = $request->lat2;
        $lon2 = $request->lon2;

        $formattedDistance = number_format($this->calculateDistance($lat1, $lon1, $lat2, $lon2), 1) . " metros";

        $endTime = microtime(true);

        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        return response()->json([
            'distance' => $formattedDistance,
        ], 200);
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

        if ($features == null) {
            return response()->json(['message' => 'sem pontos próximos'], 404);
        }
        $geojson = [
            'ex_time' => $executionTime . " MS",
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        return response()->json($geojson, 200);
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

            return response()->json([
                'length_meters' => number_format($totalLength / 2, 2) . ' metros',
            ], 200);
        }

        $totalDistance = 0;
        for ($i = 1; $i < count($linestring->coordinates); $i++) {
            $coordinates1 = $linestring->coordinates[$i - 1];
            $coordinates2 = $linestring->coordinates[$i];

            $distanceQuery = $this->calculateDistance($coordinates1[0], $coordinates1[1], $coordinates2[0], $coordinates2[1]);

            $totalDistance += $distanceQuery;
        }

        $formattedLengthMeters = number_format($totalDistance, 2);

        return response()->json([
            'length_meters' => $formattedLengthMeters . ' metros',
        ], 200);
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/buffer?latitude=-1.34538115355059&longitude=-48.4045690844909
    public function getBuffer(Request $request)
    {
        try {
            $raio = $request->has('raio') ? intval($request->raio) : null;
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if ($raio < 6) {
                return response()->json([
                    "error" => [
                        "status" => "422",
                        "title" => "Tamanho de raio não suportado",
                        "detail" => "O raio deve ser maior ou igual a 6 metros."
                    ]
                ], 422);
            }

            $buffer = $this->buffer($raio, $latitude, $longitude);
            return response()->json($buffer, 200);

        } catch (Exception $e) {
            return response()->json([
                "error" => [
                    "status" => "500",
                    "title" => "Erro na Solicitação",
                    "detail" => $e->getMessage(),
                ]
            ], 500);
        }
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
                    'coordinates' => [$pontos] // Isso forma um polígono que representa o buffer
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
