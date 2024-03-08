<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubClasse extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
        'related_color',
    ];

    public function class()
    {
        return $this->belongsTo(Classe::class);
    }
}
