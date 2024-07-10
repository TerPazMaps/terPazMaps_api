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

}
