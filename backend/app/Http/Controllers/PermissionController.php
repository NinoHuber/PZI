<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::all());
    }

    public function store(Request $request)
    {
        if(auth()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        return response()->json(Permission::create($request->all()), 201);
    }

    public function show(string $id)
    {
        return response()->json(Permission::findOrFailr($id));
    }

    public function update(Request $request, string $id)
    {
        if(auth()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Permission::find($id);
        $room->update($request->all());
        return response()->json("permission updated");
    }

    public function destroy(string $id)
    {
        if(auth()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Permission::find($id);
        $room->delete();
        return response()->json("permission deleted", 200);
    }
}
