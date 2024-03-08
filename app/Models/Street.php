<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'region_id',
        'name',
        'geometry',
        'verified_in_google',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
