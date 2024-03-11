<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'related_color',
        'related_secondary_color',
    ];

    public function subclasses()
    {
        return $this->hasMany(Subclasse::class);
    }
}
