<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activitie extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'subclass_id',
        'name',
        'geometry',
        'active',
        'level',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function subclass()
    {
        return $this->belongsTo(Subclasse::class);
    }

    
  
}
