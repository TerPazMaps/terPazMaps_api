<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ActivitieInterface
{
    public static function getAllWithRelationsAndGeometry():Builder;

}