<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'course_uuid',
        'hall_uuid',
        'start_time',
        'end_time'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_uuid', 'uuid');
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_uuid', 'uuid');
    }
}
