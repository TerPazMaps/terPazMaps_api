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

        $startTime = microtime(true);
        $query = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $subclass_id);

        $endTime = microtime(true);        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

        $startTime = microtime(true);
        $query = $query->whereRaw("ST_Distance_Sphere(PointFromText('POINT($longitude $latitude)', 4326), geometry) <= $raio");
        $endTime = microtime(true);        // Calcula o tempo total de execução em milissegundos
        $query = $query->get();
        $executionTime2 = number_format(($endTime - $startTime) * 1000, 4);


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
            'execution_time_select' => $executionTime . " milisegundos",
            'execution_time_filtro' => $executionTime2 . " milisegundos",
            'execution_time_total' => $executionTime + $executionTime2 . " total",
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
        $startTime = microtime(true);
        $activities = DB::table('activities')
            ->select('*', DB::raw('ST_AsGeoJSON(geometry) as geometry'))
            ->where('region_id', $region_id)
            ->where('subclass_id', $subclass_id);
        $endTime = microtime(true);

        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);

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
        $executionTime2 = number_format(($endTime - $startTime) * 1000, 4);

        // Formatar os dados no formato GeoJSON
        $featureCollection = [
            'execution_time_select' => $executionTime . " milisegundos",
            'execution_time_filtro' => $executionTime2 . " milisegundos",
            'execution_time_total' => $executionTime + $executionTime2 . " total",
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
        $earthRadius = 6371000; // raio da Terra em metros

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
        // http://127.0.0.1:8000/api/v5/geojson/services/distance?lat=-1.34538115355059&lon=-48.4045690844909&lat2=-1.34519276971018&lon2=-48.4041343555742 

        // Guarda o tempo de início da execução
        $startTime = microtime(true);

        // Coordenadas do primeiro ponto
        $lat1 = $request->lat;
        $lon1 = $request->lon;

        // Coordenadas do segundo ponto
        $lat2 = $request->lat2;
        $lon2 = $request->lon2;

        // Raio da Terra em metros
        $earthRadius = 6378000; // aproximadamente 6378 km * 1000 para obter o valor em metros

        // Converte graus para radianos
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        // Diferença de latitude e longitude
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        // Fórmula de Haversine
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        // Formata a distância para exibir apenas uma casa decimal e adiciona a unidade "metros"
        $formattedDistance = number_format($distance, 1) . " metros";

        // Guarda o tempo de término da execução
        $endTime = microtime(true);

        // Calcula o tempo total de execução em milissegundos
        $executionTime = number_format(($endTime - $startTime) * 1000, 4); // convertendo para milissegundos

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
}
