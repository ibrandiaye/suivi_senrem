<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

// --- Authentification Web (Espace Administration) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// --- Espace Administration Sécurisé (Admin Uniquement) ---
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Fiches de suivi terrain
    Route::prefix('fiches')->name('fiches.')->group(function () {
        Route::get('/ventes-distributeurs', [FicheController::class, 'ventesDistributeurs'])->name('ventes');
        Route::get('/animations', [FicheController::class, 'animations'])->name('animations');
        Route::get('/demonstrations', [FicheController::class, 'demonstrations'])->name('demonstrations');
        Route::get('/caravanes', [FicheController::class, 'caravanes'])->name('caravanes');
        Route::get('/emissions', [FicheController::class, 'emissions'])->name('emissions');
        Route::get('/leaders', [FicheController::class, 'leaders'])->name('leaders');
    });

    // Exports Excel
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::get('/download/{type}', [ExportController::class, 'export'])->name('download');
    });

    // Gestion des utilisateurs et affectations régionales
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
});
