<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ["subject_name", "lecturer"];

    function lecturer() {
        return $this->belongsTo(User::class, "lecturer");
    }

    function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
