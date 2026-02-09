<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|",
            "password" => "required",
            "permission_id" => "required",
            "occupation_id" => "required"
        ]);
        $user = User::create([
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"]),
            "permission_id" => $validated["permission_id"],
            "occupation_id" => $validated["occupation_id"]
        ]);
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json([
            "user" => $user,
            "access_token" => $token,
            "token_type" => "Bearer"
        ],201);
    }
    public function login(Request $request) {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);
        $user = User::where("email", $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json("invalid credentials",401);
        }
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "permission_id" => $user->permission_id
        ]);
    }
}
