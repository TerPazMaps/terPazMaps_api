<?php

namespace App\Repositories;

use App\Models\Activitie;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ActivitieInterface;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class ActivitieRepository extends AbstractRepository implements ActivitieInterface
{

    protected static $model = Activitie::class;

    public static function getAllWithRelationsAndGeometry(): Builder
    {
        return self::loadModel()::query()->select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry')
        )->has('subclass.classe')
            ->has('subclass.icon')
            ->orderBy('id');
    }

    public static function filterQuery(Builder $query, String $column, array $regions_id): Builder
    {
        return $query->whereIn($column, $regions_id);
    }
}
