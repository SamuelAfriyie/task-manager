<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/project', [ProjectController::class, 'index']);
    Route::get('/project/{id}', [ProjectController::class, 'show']);
    Route::post('/project', [ProjectController::class, 'store']);
    Route::put('/project/{project}', [ProjectController::class, 'update']);
    Route::delete('/project/{project}', [ProjectController::class, 'destroy']);


    Route::get('/task', [TaskController::class, 'index']);
    Route::get('/task/{id}', [TaskController::class, 'show']);
    Route::post('/task', [TaskController::class, 'store']);
    Route::put('/task/{task}', [TaskController::class, 'update']);
    Route::delete('/task/{task}', [TaskController::class, 'destroy']);
    Route::patch('/task/{task}', [TaskController::class, 'markComplete']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
