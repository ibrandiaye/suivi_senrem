<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

// Authentification Mobile
Route::post('/login', [AuthController::class, 'login']);

// Endpoints sécurisés pour l'application mobile (Token Bearer Sanctum requis)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Synchronisation bidirectionnelle
    Route::get('/sync/pull', [SyncController::class, 'pull']);
    Route::post('/sync/push', [SyncController::class, 'push']);
});
