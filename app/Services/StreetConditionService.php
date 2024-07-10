<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Street_condition;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class StreetConditionService extends AbstractService implements ServiceInterface
{
    protected static $model = Street_condition::class;

    public function index($user_id): Collection|false
    {
        return false;
    }

}
