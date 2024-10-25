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
    ];

    public function subclasse()
    {
        return $this->belongsTo(Subclasse::class);
    }

    public function getPath()
    {
        return env('app_url') . 'storage/' . substr($this->disk_name, 0, 3)
         . '/' . substr($this->disk_name, 3, 3)
         . '/' . substr($this->disk_name, 6, 3) 
         . '/' . $this->disk_name;
    }
}
