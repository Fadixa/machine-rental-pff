<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

// ── Auth publique ─────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── Machines publiques (sans auth) ────────────────────────────────
Route::get('/machines',        [MachineController::class, 'index']);
Route::get('/machines/{machine}', [MachineController::class, 'show']);

// ── Routes protégées (Sanctum) ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',     [AuthController::class, 'me']);

    // Machines (owner)
    Route::get('/my-machines',          [MachineController::class, 'myMachines']);
    Route::post('/machines',            [MachineController::class, 'store']);
    Route::put('/machines/{machine}',   [MachineController::class, 'update']);
    Route::delete('/machines/{machine}',[MachineController::class, 'destroy']);

    // Réservations
    Route::get('/reservations',  [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::patch('/reservations/{reservation}/accept',
        [ReservationController::class, 'accept']);
    Route::patch('/reservations/{reservation}/reject',
        [ReservationController::class, 'reject']);
    Route::patch('/reservations/{reservation}/complete',
        [ReservationController::class, 'complete']);

    // Ratings
    Route::post('/machines/{machine}/ratings', [RatingController::class, 'store']);
    Route::get('/machines/{machine}/ratings',  [RatingController::class, 'index']);
});

// Vérifier toutes les routes : php artisan route:list