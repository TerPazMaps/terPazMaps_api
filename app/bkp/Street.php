<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'region_id',
        'street_condition_id',
        'geometry',
        'properties',
        'color',
        'width',
        'continuous',
        'line_cap',
        'line_dash_pattern',
        'created_at',
        'updated_at',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function streetCondition()
    {
        return $this->belongsTo(StreetCondition::class);
    }
}
