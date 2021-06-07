<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'hall_uuid',
        'latitude',
        'longitude'
    ];

}
