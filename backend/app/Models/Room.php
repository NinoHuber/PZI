<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ["room_number"];

    function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
