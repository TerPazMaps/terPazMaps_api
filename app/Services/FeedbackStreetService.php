<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\FeedbackStreet;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class FeedbackStreetService extends AbstractService implements ServiceInterface
{
    protected static $model = FeedbackStreet::class;

    public function index($user_id): Collection|false
    {
        return self::loadModel()->query()->where('user_id', $user_id)
            ->get();
    }

    public function index_map($FeedbackStreet): Collection
    {
        return $FeedbackStreet->map(function ($FeedbackStreet) {
            return [
                "id" => $FeedbackStreet->id,
                "user_id" => $FeedbackStreet->user_id,
                "street_id" => $FeedbackStreet->street_id,
                "street_condition_id" => $FeedbackStreet->street_condition_id,
                "created_at" => Carbon::parse($FeedbackStreet->created_at)->format('d/m/Y H:i:s'),
                "updated_at" => Carbon::parse($FeedbackStreet->updated_at)->format('d/m/Y H:i:s')
            ];
        });
    }

    public function show_map($feedbackStreet): array
    {
        return [
            "id" => $feedbackStreet->id,
            "user_id" => $feedbackStreet->user_id,
            "street_id" => $feedbackStreet->street_id,
            "street_condition_id" => $feedbackStreet->street_condition_id,
            "created_at" => Carbon::parse($feedbackStreet->created_at)->format('d/m/Y H:i:s'),
            "updated_at" => Carbon::parse($feedbackStreet->updated_at)->format('d/m/Y H:i:s')
        ];
    }
}
