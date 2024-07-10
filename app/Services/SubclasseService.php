<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Subclasse;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Support\Collection;

class SubclasseService extends AbstractService implements ServiceInterface
{
    protected static $model = Subclasse::class;

    public function index($user_id): Collection|false
    {
        return false;
    }

}
