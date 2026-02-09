<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ["room_id", "subject_id", "day_of_the_week", "start_time", "end_time", "created_by"];

    function subject() {
        return $this->belongsTo(Subject::class);
    }

    function room() {
        return $this->belongsTo(Room::class);
    }

    function creator() {
        return $this->belongsTo(User::class, "created_by");
    }
}
