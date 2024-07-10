<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Icon;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class IconService extends AbstractService implements ServiceInterface
{
    protected static $model = Icon::class;

    public function index($user_id): Collection|false
    {
        return false;
    }

}
