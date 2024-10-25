<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'geometry',
        'center',
    ];

    public function streets()
    {
        return $this->hasMany(Street::class);
    }
    
    public function subclass()
    {
        return $this->hasMany(Subclasse::class);
    }
    
    public function activities()
    {
        return $this->hasMany(activitie::class);
    }

}
