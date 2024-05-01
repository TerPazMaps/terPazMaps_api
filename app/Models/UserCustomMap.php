<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'geometry',
        'center',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
