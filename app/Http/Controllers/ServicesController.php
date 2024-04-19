<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    public function getActivitiesbyArea(Request $request)
    {
        // Guarda o tempo de início da execução
        // Método para buscar atividades próximas de uma coordenada e raio específico
        // http://127.0.0.1:8000/api/v5/geojson/services/activities-nearby?region_id=7&subclass_id=28&raio=3000&latitude=-1.465815&longitude=-48.459401

        $region_id = $request->input('region_id');
        $subclass_id = $request->input('subclass_id');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio');

        $query = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $subclass_id);

        $startTime = microtime(true);
        $query = $query->whereRaw("ST_Distance_Sphere(PointFromText('POINT($longitude $latitude)', 4326), geometry) <= $raio");
        $endTime = microtime(true);        // Calcula o tempo total de execução em milissegundos
        $query = $query->get();
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

        $featureCollection = [
            'execution_time' => $executionTime . " milisegundos",
            'function' => "ST_Distance_Sphere",
            "type" => "FeatureCollection",
            "features" => $activities->toArray(),
        ];
        return $featureCollection;
    }

    public function getActivitiesbyArea2(Request $request)
    {

        // Método para buscar atividades próximas de uma coordenada e raio específico
        // http://127.0.0.1:8000/api/v5/geojson/services/activities-nearby2?region_id=7&subclass_id=28&raio=3000&latitude=-1.465815&longitude=-48.459401

        $region_id = $request->input('region_id');
        $subclass_id = $request->input('subclass_id');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio');

        // Obter todos os pontos da região e subclasse
        $activities = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $subclass_id);
        $activities = $activities->get();

        // Filtrar os pontos que estão dentro do raio especificado
        $startTime = microtime(true);
        $filteredActivities = $activities->filter(function ($activitie) use ($latitude, $longitude, $raio) {
            $geometry = json_decode($activitie->geometry);
            $coordinates = $geometry->coordinates;
            $pointLatitude = $coordinates[1]; // latitude
            $pointLongitude = $coordinates[0]; // longitude

            // Verificar se o ponto está dentro do raio especificado
            $distance = $this->calculateDistance($latitude, $longitude, $pointLatitude, $pointLongitude);
            return $distance <= $raio;
        });
        // Guarda o tempo de término da execução
        $endTime = microtime(true);

        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        // Formatar os dados no formato GeoJSON
        $featureCollection = [
            'execution_time' => $executionTime . " milisegundos",
            'function' => "php",
            "type" => "FeatureCollection",
            "features" => $filteredActivities->map(function ($activitie) {
                $geometry = json_decode($activitie->geometry);
                $coordinates = $geometry->coordinates;
                $type = $geometry->type;

                $properties = [
                    "id" => $activitie->id,
                    "region_id" => $activitie->region_id,
                    "subclass_id" => $activitie->subclass_id,
                    "name" => $activitie->name,
                ];

                return [
                    "type" => "Feature",
                    "geometry" => [
                        "type" => $type,
                        "coordinates" => $coordinates
                    ],
                    "properties" => $properties,
                ];
            })->toArray(),
        ];

        return $featureCollection;
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

    public function getDistance(Request $request)
    {
        // http://127.0.0.1:8000/api/v5/geojson/services/distance?id=6&id2=1
        // Coordenadas do primeiro ponto
        $id1 = $request->id;
        // Coordenadas do segundo ponto
        $id2 = $request->id2;

        // Consulta para obter as coordenadas dos pontos
        $points = DB::table('activities')
            ->select('id', DB::raw('ST_AsText(geometry) as coordinates'))
            ->whereIn('id', [$id1, $id2])
            ->get();

        // Extrai as coordenadas dos pontos
        $coordinates1 = $this->extractCoordinates($points->first()->coordinates);
        $coordinates2 = $this->extractCoordinates($points->last()->coordinates);

        // Guarda o tempo de início da execução
        $startTime = microtime(true);
        // Calcula a distância entre os pontos
        $distanceQuery = DB::selectOne(
            "SELECT ST_Distance_Sphere(
                POINT($coordinates1[0], $coordinates1[1]),
                POINT($coordinates2[0], $coordinates2[1])
            ) as distance"
        );

        // Distância em metros
        $distance = number_format($distanceQuery->distance, 1) . " metros";

        // Guarda o tempo de término da execução
        $endTime = microtime(true);

        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4); // convertendo para milissegundos

        // Retorna a distância formatada e o tempo de execução
        return [
            'distance' => $distance,
            'execution_time' => $executionTime . " milissegundos",
            'function' => "ST_Distance_Sphere"
        ];
    }

    public function getDistance2(Request $request)
    {
        // http://127.0.0.1:8000/api/v5/geojson/services/distance2?lat=-1.34538115355059&lon=-48.4045690844909&lat2=-1.34519276971018&lon2=-48.4041343555742 

        // Guarda o tempo de início da execução
        $startTime = microtime(true);

        // Coordenadas do primeiro ponto
        $lat1 = $request->lat;
        $lon1 = $request->lon;

        // Coordenadas do segundo ponto
        $lat2 = $request->lat2;
        $lon2 = $request->lon2;

        // Formata a distância para exibir apenas uma casa decimal e adiciona a unidade "metros"
        $formattedDistance = number_format($this->calculateDistance($lat1, $lon1, $lat2, $lon2), 1) . " metros";

        // Guarda o tempo de término da execução
        $endTime = microtime(true);

        // Calcula o tempo total de execução em milissegundos
        // convertendo para milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        // Retorna a distância formatada e o tempo de execução
        return [
            'distance' => $formattedDistance,
            'execution_time' => $executionTime . " milissegundos",
            'function' => "php"
        ];
    }

    // Função para extrair as coordenadas de um ponto no formato "POINT(longitude latitude)"
    protected function extractCoordinates($point)
    {
        // Remove os caracteres não numéricos e divide as coordenadas
        $coordinates = explode(' ', str_replace(['POINT(', ')'], '', $point));
        return $coordinates;
    }


    // http://127.0.0.1:8000/api/v5/geojson/services/points-of-interest?region_id=1&referenciaId=7&pontoBuscadoId=28&raio=100
    public function getEscolas(Request $request)
    {
        $region_id = $request->region_id;
        $referenciaId = $request->referenciaId;
        $pontoBuscadoId = $request->pontoBuscadoId;
        $raio = $request->raio; // Raio em metros para verificar a proximidade

        // Consulta para obter as coordenadas das referencias
        $referencias = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $referenciaId)
            ->get();

        // Array para armazenar as referencias e pontosBuscados no formato GeoJSON
        $features = [];

        // Consulta para obter as escolas próximas à igreja atual
        $pontosProximos = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('subclass_id', $pontoBuscadoId);

        $startTime = microtime(true);
        // Iterar sobre as referencias
        foreach ($referencias as $referencia) {
            $geometry = json_decode($referencia->geometry);
            $coordinates = $geometry->coordinates;
            $latitude = $coordinates[1];
            $longitude = $coordinates[0];

            // Consulta para obter as escolas próximas à igreja atual
            $pontosProximosClone = clone $pontosProximos;

            $pontosProximosClone = $pontosProximosClone->whereRaw(
                "ST_Distance_Sphere(ST_GeomFromText('POINT($longitude $latitude)', 4326), geometry) <= $raio"
            )
                ->get();

            // Se houver pontos próximos, adiciona a referência e suas escolas ao resultado
            if ($pontosProximosClone->count() > 0) {
                $referenciasComPontosProximos = [
                    'type' => 'Feature',
                    'geometry' => json_decode($referencia->geometry),
                    'properties' => [
                        'id' => $referencia->id,
                        'region_id' => $referencia->region_id,
                        'subclass_id' => $referencia->subclass_id,
                        'name' => $referencia->name,
                        'marker-color' => '#FF0000'
                    ]
                ];
                $features[] = $referenciasComPontosProximos;

                // Adiciona os pontos proximos da referencia
                foreach ($pontosProximosClone as $ponto) {
                    $featurePontos = [
                        'type' => 'Feature',
                        'geometry' => json_decode($ponto->geometry),
                        'properties' => [
                            'id' => $ponto->id,
                            'region_id' => $ponto->region_id,
                            'subclass_id' => $ponto->subclass_id,
                            'name' => $ponto->name,
                        ]
                    ];
                    $features[] = $featurePontos;
                }
            }
        }
        $endTime = microtime(true);        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        $geojson = [
            'ex_time' => $executionTime . " MS",
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        return $geojson;
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/points-of-interest-php?region_id=1&referenciaId=7&pontoBuscadoId=28&raio=100
    public function getEscolas2(Request $request)
    {
        $region_id = $request->region_id;
        $referenciaId = $request->referenciaId;
        $pontoBuscadoId = $request->pontoBuscadoId;
        $raio = $request->raio; // Raio em metros para verificar a proximidade

        // Consulta para obter as coordenadas das referencias
        $referencias = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $referenciaId)
            ->get();
        // Array para armazenar as referencias e pontosBuscados no formato GeoJSON
        $features = [];

        // Consulta para obter as escolas próximas à igreja atual
        $pontosProximos = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('subclass_id', $pontoBuscadoId)
            ->get();

        $startTime = microtime(true);
        // Iterar sobre as referencias
        foreach ($referencias as $referencia) {

            $geometry = json_decode($referencia->geometry);
            $coordinates = $geometry->coordinates;
            $latitude = $coordinates[1];
            $longitude = $coordinates[0];

            $pontosProximosClone = clone $pontosProximos;

            $pontosProximosClone = $pontosProximosClone->filter(function ($pontos) use ($latitude, $longitude, $raio, $pontoBuscadoId) {
                $geometry = json_decode($pontos->geometry);
                $coordinates = $geometry->coordinates;
                $pointLatitude = $coordinates[1]; // latitude
                $pointLongitude = $coordinates[0]; // longitude

                $distance = $this->calculateDistance($latitude, $longitude, $pointLatitude, $pointLongitude);
                return $distance <= $raio;
            });


            // Se houver pontos próximos, adiciona a referência e suas escolas ao resultado
            if ($pontosProximosClone->count() > 0) {
                $referenciasComPontosProximos = [
                    'type' => 'Feature',
                    'geometry' => json_decode($referencia->geometry),
                    'properties' => [
                        'id' => $referencia->id,
                        'region_id' => $referencia->region_id,
                        'subclass_id' => $referencia->subclass_id,
                        'name' => $referencia->name,
                        'marker-color' => '#FF0000'
                    ]
                ];

                $features[] = $referenciasComPontosProximos;

                // Adiciona os pontos proximos da referencia
                foreach ($pontosProximosClone as $ponto) {
                    $featurePontos = [
                        'type' => 'Feature',
                        'geometry' => json_decode($ponto->geometry),
                        'properties' => [
                            'id' => $ponto->id,
                            'region_id' => $ponto->region_id,
                            'subclass_id' => $ponto->subclass_id,
                            'name' => $ponto->name,
                        ]
                    ];
                    $features[] = $featurePontos;
                }
            }
        }

        // Calcula o tempo total de execução em milissegundos
        $endTime = microtime(true);
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        $geojson = [
            'ex_time' => $executionTime . " MS",
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        return $geojson;
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/length-street?street_id=17455
    public function getLengthStreet(Request $request)
    {
        $street_id = $request->street_id;
        $street = DB::table('streets')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('id', $street_id)
            ->first();

        $linestring = json_decode($street->geometry);

        // Verifica se a geometria da rua é um polígono
        if ($linestring->type === 'Polygon') {
            // Consulta para calcular o comprimento do polígono            
            $startTime = microtime(true);
            $lengthQuery = DB::selectOne(
                "SELECT ROUND((ST_Length(ST_ExteriorRing(geometry)) / 2) * 111320 , 2) AS length_meters
            FROM streets
            WHERE id = $street_id"
            );
            $endTime = microtime(true);
            $executionTime = number_format(($endTime - $startTime) * 1000, 4);

            return response()->json([
                'execution_time' => $executionTime . " ms",
                'length_meters' => $lengthQuery->length_meters . ' metros',
                'tipo' => ' polygon',
                'line' => $linestring,
            ]);
        }


        // Itera sobre os pontos para calcular a distância entre eles
        $totalDistance = 0;
        $startTime = microtime(true);
        for ($i = 1; $i < count($linestring->coordinates); $i++) {
            $coordinates1 = $linestring->coordinates[$i - 1];
            $coordinates2 = $linestring->coordinates[$i];

            // Consulta para calcular a distância entre os pontos
            $distanceQuery = DB::selectOne(
                "SELECT ST_Distance_Sphere(
                POINT($coordinates1[0], $coordinates1[1]),
                POINT($coordinates2[0], $coordinates2[1])
            ) as distance"
            );

            // Soma a distância calculada ao total
            $totalDistance += $distanceQuery->distance;
        }
        $endTime = microtime(true);
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);


        // Formatação do comprimento em metros
        $formattedLengthMeters = number_format($totalDistance, 2);

        return response()->json([
            'length_meters' => $formattedLengthMeters . ' metros',
            'tipo' => ' Linestring',
            'execution_time' => $executionTime . ' ms',
        ]);
    }

    // http://127.0.0.1:8000/api/v5/geojson/services/length-street2?street_id=1
    public function getLengthStreet2(Request $request)
    {
        $street_id = $request->street_id;
        $street = DB::table('streets')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('id', $street_id)
            ->first();

        $linestring = json_decode($street->geometry);

        // Verifica se a geometria da rua é um polígono
        if ($linestring->type === 'Polygon') {
            $coordinates = $linestring->coordinates[0]; // Obtém as coordenadas do anel exterior

            // Inicializa a variável para armazenar o comprimento total
            $totalLength = 0;

            // Calcula a distância entre os pontos consecutivos no polígono
            $startTime = microtime(true);
            for ($i = 0; $i < count($coordinates) - 1; $i++) {
                $lon1 = $coordinates[$i][0];
                $lat1 = $coordinates[$i][1];
                $lon2 = $coordinates[$i + 1][0];
                $lat2 = $coordinates[$i + 1][1];

                // Use a função calculateDistance() para calcular a distância
                $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);

                // Adiciona a distância ao comprimento total
                $totalLength += $distance;
            }
            $endTime = microtime(true);
            $executionTime = number_format(($endTime - $startTime) * 1000, 4);

            // Ajusta a resposta JSON
            return response()->json([
                'execution_time' => $executionTime . " ms",
                'length_meters' => round($totalLength / 2, 2) . ' metros',
                'tipo' => 'polygon',
                'function' => 'php',
                'line' => $linestring,
            ]);
        }

        // Itera sobre os pontos para calcular a distância entre eles
        $totalDistance = 0;
        $startTime = microtime(true);
        for ($i = 1; $i < count($linestring->coordinates); $i++) {
            $coordinates1 = $linestring->coordinates[$i - 1];
            $coordinates2 = $linestring->coordinates[$i];

            // Consulta para calcular a distância entre os pontos
            $distanceQuery = $this->calculateDistance($coordinates1[0], $coordinates1[1], $coordinates2[0], $coordinates2[1]);

            // Soma a distância calculada ao total
            $totalDistance += $distanceQuery;
        }
        $endTime = microtime(true);
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        // Formatação do comprimento em metros
        $formattedLengthMeters = number_format($totalDistance, 2);

        return response()->json([
            'length_meters' => $formattedLengthMeters . ' metros',
            'tipo' => ' Linestring',
            'function' => 'php',
            'execution_time' => $executionTime . " ms",
        ]);
    }
    // http://127.0.0.1:8000/api/v5/geojson/services/buffer?id=1
    public function buffer(Request $request)
    {
        $street_id = $request->id;
        $street = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('id', $street_id)
            ->first();

        $geometry = json_decode($street->geometry);
        $coordinates = $geometry->coordinates;

        $buffer = DB::selectOne(
            "SELECT ST_AsGeoJSON(ST_Buffer(ST_GeomFromText('POINT($coordinates[0] $coordinates[1])', 4326), 0.0009)) as buf"
        );

        // Ajusta a resposta JSON
        return response()->json([

            'type' => 'Feature',
            'geometry' => json_decode($buffer->buf),
            'properties' => [
                'teste' => 'teste2'
            ]

        ]);
    }
}
