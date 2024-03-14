<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    protected $fillable = [
        'subclasse_id',
        'disk_name',
        'file_name',
        'file_size',
        'content_type',
        'title',
        'description',
        'field',
        'attachment_type',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function subclasse()
    {
        return $this->belongsTo(Subclasse::class, 'subclasse_id');
    }

}
