<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedbackActivitie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
