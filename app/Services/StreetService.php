<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Street;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class StreetService extends AbstractService implements ServiceInterface
{
    protected static $model = Street::class;

    public function index($user_id): Collection|false
    {
        return false;
    }

}
