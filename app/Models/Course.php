<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'course_code',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'courses_users', 'course_id', 'user_id')->withPivot('status')->withTimestamps();
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'course_uuid', 'uuid');
    }
    
}
