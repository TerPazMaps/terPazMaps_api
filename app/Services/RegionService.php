<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class RegionService extends AbstractService implements ServiceInterface
{
    protected static $model = Region::class;

    public function index($user_id): Collection|false
    {
        return false;
    }

}
