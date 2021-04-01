<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_uuid',
        'ip_address',
        'mac_adress'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
