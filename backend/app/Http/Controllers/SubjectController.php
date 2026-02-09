<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::all());
    }

    public function store(Request $request)
    {
        if($request->user()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        return response()->json(Subject::create($request->all()), 201);
    }

    public function show(string $id)
    {
        return response()->json(Subject::findOrFailr($id));
    }

    public function update(Request $request, string $id)
    {
        if($request->user()->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Subject::find($id);
        $room->update($request->all());
        return response()->json("subject updated");
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        if($user->permission_id !== 1) {
            return response()->json("unauthorized, admin only", 403);
        }
        $room = Subject::find($id);
        $room->delete();
        return response()->json("subject deleted", 200);
    }
}
