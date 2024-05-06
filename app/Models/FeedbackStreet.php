<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackStreet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'street_id',
        'street_condition_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    
    public function streetCondition()
    {
        return $this->belongsTo(Street_condition::class);
    }
}
