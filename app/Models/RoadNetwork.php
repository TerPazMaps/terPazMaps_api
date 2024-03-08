<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadNetwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'name',
        'geometry',
        'properties',
        'color',
        'width',
        'continuous',
        'line_cap',
        'line_dash_pattern',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
