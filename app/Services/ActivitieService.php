<?php

namespace App\Services;

use App\Models\Activitie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Builder;

class ActivitieService extends AbstractService implements ServiceInterface
{
    protected static $model = Activitie::class;

    public function getAllWithRelationsAndGeometry(): Builder
    {
        return self::loadModel()::query()->select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry')
        )->has('subclass.classe')
            ->has('subclass.icon')
            ->orderBy('id');
    }

    public function filter(Request $request, Builder $query): Builder
    {
        if ($request->regions) {
            $regions_id = array_map('intval', explode(',', $request->regions));
            $query = $query->whereIn('region_id', $regions_id);
        }

        if ($request->subclasses) {
            $subclasses_id = array_map('intval', explode(',', $request->subclasses));
            $query = $query->whereIn('subclass_id', $subclasses_id);
        }

        if ($request->ids) {
            $ids = array_map('intval', explode(',', $request->ids));
            $query = $query->whereIn('id', $ids);
        }

        return $query;
    }

    public static function activitiesMap($request, $activitiesCollection, $startTime)
    {
        if ($request->only_references) {
            $mappedActivities = $activitiesCollection
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
            $mappedActivities = $activitiesCollection
                ->map(function ($activity) {
                    // Construa a URL da imagem do ícone
    
                    $geojson_activity = [
                        "type" => "Feature",
                        "geometry" => json_decode($activity->geometry),
                        "properties" => [
                            "ID Geral" => $activity->id,
                            "Nome" => $activity->name ?? '',
                            "Classe" => $activity->subclass->classe->name ?? '',
                            "Sub-classe" => $activity->subclass->name,
                            "Bairro_id" => $activity->region->id,
                            "Bairro" => $activity->region->name,
                            "Nível" => $activity->level,
                            "img_url" => 'http://127.0.0.1:8000/storage/' . substr($activity->subclass->icon->disk_name, 0, 3) . '/' . substr($activity->subclass->icon->disk_name, 3, 3) . '/' . substr($activity->subclass->icon->disk_name, 6, 3) . '/' . $activity->subclass->icon->disk_name
                        ]
                    ];

                    return $geojson_activity;
                });
        }

        $endTime = microtime(true);
        $executionTime = number_format(($endTime - $startTime) * 1000, 4);
        return [
            "geojson" => [
                "time" => $executionTime,
                "type" => "FeatureCollection",
                "features" => $mappedActivities
            ]
        ];
    }
}
