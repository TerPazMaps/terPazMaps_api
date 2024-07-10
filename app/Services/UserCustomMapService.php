<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\UserCustomMap;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class SubclasseService extends AbstractService implements ServiceInterface
{
    protected static $model = UserCustomMap::class;

    public function index($user_id): Collection|false
    {
        return false;
    }

}
