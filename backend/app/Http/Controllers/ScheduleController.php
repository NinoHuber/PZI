<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Gate;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(["room", "subject.lecturer", "creator"])->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "room_id" => "required",
            "subject_id" => "required",
            "day_of_the_week" => "required",
            "start_time" => "required",
            "end_time" => "required"
        ]);
        $overlap = Schedule::where("room_id", $validated["room_id"])
        ->where("day_of_the_week", $validated["day_of_the_week"])
        ->where(function($query) use ($validated) {
            $query->where("start_time", "<", $validated["end_time"])
            ->where("end_time", ">", $validated["start_time"]);
        })
        ->exists();
        if($overlap) {
            return response()->json("error: schedule conflict", 422);
        }
        $creatorID = auth()->guard()->id();
        $schedule = Schedule::create(array_merge($validated, ["created_by" => $creatorID]));
        return response()->json($schedule->load(["room", "subject.lecturer"]), 201);
    }

    public function show(string $id)
    {
        $schedule = Schedule::find($id);
        return response()->json($schedule->load(["room", "subject.lecturer", "creator"]),201);
    }

    public function update(Request $request, string $id)
    {
        $schedule = Schedule::find($id);
        Gate::authorize("update", $schedule);
        $validated = $request->validate([
            "room_id" => "sometimes",
            "day_of_the_week" => "sometimes",
            "start_time" => "sometimes",
            "end_time" => "sometimes"
        ]);
        $roomId = $validated["room_id"] ?? $schedule->room_id;
        $day = $validated["day_of_the_week"] ?? $schedule->day_of_the_week;
        $start = $validated["start_time"] ?? $schedule->start_time;
        $end = $validated["end_time"] ?? $schedule->end_time;
        $overlap = Schedule::where("id", "!=", $schedule->id)
        ->where("room_id", $roomId)
        ->where("day_of_the_week", $day)
        ->where(function($query) use ($start, $end) {
            $query->where("start_time", "<", $end)
            ->where("end_time", ">", $start);
        })
        ->exists();
        if($overlap) {
            return response()->json("error: schedule conflict", 422);
        }
        $schedule->update($validated);
        return response()->json($schedule->load(["room", "subject.lecturer"]));
    }

    public function destroy(string $id)
    {
        $schedule = Schedule::find($id);
        Gate::authorize("delete", $schedule);
        $schedule->delete();
        return response()->json("schedule deleted");
    }
}
