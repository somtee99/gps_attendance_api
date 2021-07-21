<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name'
        // 'longitude',
        // 'latitude',
        // 'elevation'
    ];
    
    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'hall_uuid', 'uuid');
    }
}
