<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function index()
    {
        return response()->json(Occupation::all());
    }

    public function store(Request $request)
    {
        if(auth()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        return response()->json(Occupation::create($request->all()), 201);
    }

    public function show(string $id)
    {
        return response()->json(Occupation::findOrFailr($id));
    }

    public function update(Request $request, string $id)
    {
        if(auth()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Occupation::find($id);
        $room->update($request->all());
        return response()->json("occupation updated");
    }

    public function destroy(string $id)
    {
        if(auth()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Occupation::find($id);
        $room->delete();
        return response()->json("occupation deleted", 200);
    }
}
