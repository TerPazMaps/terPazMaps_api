<?php

namespace App\Services;

use App\Models\Icon;
use Illuminate\Support\Collection;
use App\Interfaces\ServiceInterface;

class IconService extends AbstractService implements ServiceInterface
{
    protected static $model = Icon::class;

    public function index(): Collection|false
    {
        return self::loadModel()->query()->with('subclasse')
        ->has('subclasse') // Somente ícones que têm uma atividade relacionada com uma subclass correspondente
        ->get();
    }

}
