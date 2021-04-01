<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function courses(){
        return $this->belongsToMany(Course::class, 'courses_users', 'user_id', 'course_id')->withPivot('status')->withTimestamps();
    }

    public function attendances(){
        return $this->hasMany(Attendance::class, 'user_uuid', 'uuid');
    }

    public function device(){
        return $this->hasOne(Device::class, 'user_uuid', 'uuid');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'middle_name',
        'last_name',
        'matric_no',
        'user_type',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
