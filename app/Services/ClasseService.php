<?php

namespace App\Services;

use App\Models\Classe;
use Illuminate\Http\Request;
use App\Interfaces\ServiceInterface;
use App\Models\Subclasse;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class ClasseService extends AbstractService implements ServiceInterface
{
    protected static $model = Classe::class;

    public function index()
    {
        $collection = self::loadModel()::query()->select('*')
            ->paginate(12);

            
        $collection->getCollection()->transform(function ($classe) {
            return [
                "id" => $classe->id,
                "name" => $classe->name,
                "related_color" => $classe->related_color,
                "related_secondary_color" => $classe->related_secondary_color
            ];
        });

        return $collection;
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

    public function getSubclassesByClass(int $id): Paginator
    {
        $subclasses = Subclasse::query()->select('*')->where('class_id', $id)
            ->has('classe')
            ->has('related_icon')
            ->paginate(12);

        $subclasses->getCollection()->transform(function ($subclasse) {
            return [ 
                "id" => $subclasse->id,
                "name" => $subclasse->name,
                "related_color" => $subclasse->related_color,
                "classe" =>[
                    'id'=>$subclasse->classe->id,
                    'name'=>$subclasse->classe->name,
                    'related_color'=>$subclasse->classe->related_color,
                ],
                "related_icon" =>[
                    'id'=>$subclasse->related_icon->id,
                    'name'=>$subclasse->related_icon->name,
                    'disk_name'=>$subclasse->related_icon->disk_name,
                    'file_name'=>$subclasse->related_icon->file_name,
                    'path2'=>  $subclasse->related_icon->getPath(),
                ]
            ];
        });

        return $subclasses;
    }

}
