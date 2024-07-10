<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\FeedbackActivitie;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class FeedbackActivitieService extends AbstractService implements ServiceInterface
{
    protected static $model = FeedbackActivitie::class;

    public function index($user_id): Collection|false
    {
        return self::loadModel()::query()->select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
        )
            ->where('user_id', $user_id)
            ->get();
    }

    public function index_map($FeedbackActivitie): Collection
    {
        return $FeedbackActivitie->map(function ($FeedbackActivitie) {
            return [
                "geojson" => [
                    "type" => "Feature",
                    "geometry" => json_decode($FeedbackActivitie->geometry),
                    "properties" => [
                        "id" => $FeedbackActivitie->id,
                        "user_id" => $FeedbackActivitie->user_id,
                        "name" => $FeedbackActivitie->name,
                        "region_id" => $FeedbackActivitie->region_id,
                        "subclass_id" => $FeedbackActivitie->subclass_id,
                        "created_at" => Carbon::parse($FeedbackActivitie->created_at)->format('d/m/Y H:i:s'),
                        "updated_at" => Carbon::parse($FeedbackActivitie->updated_at)->format('d/m/Y H:i:s'),
                    ]
                ]
            ];
        });
    }

    public function show($id): FeedbackActivitie|null
    {
        return self::loadModel()::query()->select(
            '*',
            DB::raw('ST_AsGeoJSON(geometry) as geometry'),
        )
            ->find($id);
    }


    public function show_map($FeedbackActivitie): array
    {
        return [
            "geojson" => [
                "type" => "Feature",
                "geometry" => json_decode($FeedbackActivitie->geometry),
                "properties" => [
                    "id" => $FeedbackActivitie->id,
                    "user_id" => $FeedbackActivitie->user_id,
                    "name" => $FeedbackActivitie->name,
                    "region_id" => $FeedbackActivitie->region_id,
                    "subclass_id" => $FeedbackActivitie->subclass_id,
                    "created_at" => Carbon::parse($FeedbackActivitie->created_at)->format('d/m/Y H:i:s'),
                    "updated_at" => Carbon::parse($FeedbackActivitie->updated_at)->format('d/m/Y H:i:s'),
                ]
            ]
        ];
    }
}
