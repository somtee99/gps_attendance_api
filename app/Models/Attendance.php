<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_uuid',
        'lecture_uuid',
        'type',
        'longitude',
        'latitude',
        'elevation'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function lecture(){
        return $this->belongsTo(Lecture::class, 'lecture_uuid', 'uuid');
    }
}
