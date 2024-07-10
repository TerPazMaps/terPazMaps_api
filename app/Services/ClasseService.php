<?php

namespace App\Services;

use App\Models\Classe;
use Illuminate\Http\Request;
use App\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class ClasseService extends AbstractService implements ServiceInterface
{
    protected static $model = Classe::class;

    public function index(): array
    {
        $collection = self::loadModel()::query()->select(
            'id',
            'name',
            'related_color',
            'related_secondary_color'
        )
            ->get()
            ->map(function ($classe) {
                $classe = [
                    "Classe" => [
                        "ID" => $classe->id,
                        "Nome" => $classe->name,
                        "related_color" => $classe->related_color,
                        "related_secondary_color" => $classe->related_secondary_color
                    ]
                ];
                return $classe;
            });

        return ["geojson" => $collection];
    }

    public function show(int $id): array
    {
        $collection = self::loadModel()::query()->select(
            'id',
            'name',
            'related_color',
            'related_secondary_color'
        )->find($id);

        return ["geojson" => $collection];
    }

    public function getSubclassesByClass(int $id): array
    {
        $classes = self::loadModel()::query()->where('id', $id)
            ->has('subclasse')
            ->has('subclasse.icon')
            ->paginate(15);

        foreach ($classes as $cl) {
            foreach ($cl->subclasse as $subclasse) {
                $icon = $subclasse->icon;
                $icon->image_url = config('app.url') . 'storage/' . substr($icon->disk_name, 0, 3) . '/' . substr($icon->disk_name, 3, 3) . '/' . substr($icon->disk_name, 6, 3) . '/' . $icon->disk_name;
            }
        }

        return ["geojson" => $classes];
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
