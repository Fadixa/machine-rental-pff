<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| API ROUTES — Rentify
| Base URL : /api
| Auth : Laravel Sanctum (Bearer token dans le header Authorization)
|
| IMPORTANT — Laravel 11 :
| Lancer d'abord : php artisan install:api
|--------------------------------------------------------------------------
*/

/* ══════════════════════════════════════════════════════════════
   AUTH — Routes publiques (sans token)
══════════════════════════════════════════════════════════════ */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

/* ══════════════════════════════════════════════════════════════
   MACHINES — Consultation publique (sans token)
══════════════════════════════════════════════════════════════ */
Route::get('/machines',         [MachineController::class, 'index']);   // GET  /api/machines?type=&location=&status=available
Route::get('/machines/{machine}', [MachineController::class, 'show']); // GET  /api/machines/{id}

/* Ratings publics */
Route::get('/machines/{machine}/ratings', [RatingController::class, 'index']); // GET  /api/machines/{id}/ratings

/* ══════════════════════════════════════════════════════════════
   ROUTES PROTÉGÉES — Nécessitent un token Sanctum valide
══════════════════════════════════════════════════════════════ */
Route::middleware('auth:sanctum')->group(function () {

    /* ── Auth ── */
    Route::post('/logout', [AuthController::class, 'logout']); // POST /api/logout
    Route::get('/me',      [AuthController::class, 'me']);     // GET  /api/me

    /* ── Machines — actions owner ── */
    Route::get('/my-machines',              [MachineController::class, 'myMachines']); // GET  /api/my-machines
    Route::post('/machines',                [MachineController::class, 'store']);       // POST /api/machines
    Route::put('/machines/{machine}',       [MachineController::class, 'update']);      // PUT  /api/machines/{id}
    Route::patch('/machines/{machine}',     [MachineController::class, 'update']);      // PATCH (alias)
    Route::delete('/machines/{machine}',    [MachineController::class, 'destroy']);     // DELETE /api/machines/{id}

    /* ── Réservations ── */
    Route::get('/reservations',             [ReservationController::class, 'index']);   // GET  /api/reservations
    Route::post('/reservations',            [ReservationController::class, 'store']);   // POST /api/reservations
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']); // GET  /api/reservations/{id}

    /* Transitions de statut */
    Route::patch('/reservations/{reservation}/accept',   [ReservationController::class, 'accept']);   // PATCH .../accept
    Route::patch('/reservations/{reservation}/reject',   [ReservationController::class, 'reject']);   // PATCH .../reject
    Route::patch('/reservations/{reservation}/complete', [ReservationController::class, 'complete']); // PATCH .../complete
    Route::patch('/reservations/{reservation}/cancel',   [ReservationController::class, 'cancel']);   // PATCH .../cancel

    /* ── Ratings ── */
    Route::post('/machines/{machine}/ratings', [RatingController::class, 'store']);    // POST /api/machines/{id}/ratings
});