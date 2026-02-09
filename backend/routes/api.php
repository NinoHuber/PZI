<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Models\Occupation;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/subjects', [SubjectController::class, 'index']);
Route::apiResource("permissions", PermissionController::class);
Route::apiResource("occupations", OccupationController::class);

Route::get("/professors", [UserController::class, "getProfessors"]);

Route::get('/occupations', function() {
    return Occupation::all();
});

Route::get('/permissions', function() {
    return Permission::all();
});

Route::get('/users', [UserController::class, 'index']);

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::get("/schedules", [ScheduleController::class, "index"]);
Route::get("/schedule/{id}", [ScheduleController::class, "show"]);
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::post("/schedules", [ScheduleController::class, "store"]);
    Route::put("/schedules/{id}", [ScheduleController::class, "update"]);
    Route::delete("/schedule/{id}", [ScheduleController::class, "destroy"]);

    Route::post('/rooms', [RoomController::class, 'store']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::put('/subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

    Route::put('/users/{id}/role', [UserController::class, 'updateRole']);
});