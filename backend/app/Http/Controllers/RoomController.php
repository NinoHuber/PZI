<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(Room::all());
    }

    public function store(Request $request)
    {
        if($request->user()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        return response()->json(Room::create($request->all()), 201);
    }

    public function show(string $id)
    {
        return response()->json(Room::findOrFailr($id));
    }

    public function update(Request $request, string $id)
    {
        if($request->user()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Room::find($id);
        $room->update($request->all());
        return response()->json("room updated");
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        if($user->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Room::find($id);
        $room->delete();
        return response()->json("room deleted", 200);
    }
}
