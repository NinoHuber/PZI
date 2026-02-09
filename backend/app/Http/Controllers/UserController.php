<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function getProfessors() {
        $professors = User::whereHas('occupation', function($query) {
            $query->where('occupation', 'Professor');
        })->get();
        return response()->json($professors);
    }

    public function updateRole(Request $request, $id) {
    if ($request->user()->permission_id !== 1) {
        return response()->json("Unauthorized", 403);
    }

    $validated = $request->validate([
        'occupation_id' => 'required|exists:occupations,id',
        'permission_id' => 'required|exists:permissions,id',
    ]);

    $user = User::findOrFail($id);
    $user->update($validated);
    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function index()
    {
        return User::with(['occupation', 'permission'])->get();
    }
}
