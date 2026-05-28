<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ChatbotController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/machines/map-data', [MachineController::class, 'getMapData']);
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

// routes/api.php

Route::middleware('auth:sanctum')->group(function () {

    // ... routes existantes ...

    /*
    |--------------------------------------------------------------------------
    | Contrat PDF — Feature 2
    |--------------------------------------------------------------------------
    | Téléchargement du contrat PDF pour une réservation confirmée.
    | Accessible par le client et le propriétaire concernés.
    */
    Route::get('/reservations/{reservation}/contrat', [ReservationController::class, 'downloadContrat'])
         ->name('reservations.contrat');

});

// FEATURE 6 : Chatbot
Route::post('/chatbot', [ChatbotController::class, 'repondre']);

// ─── ADMIN ROUTES ────────────────────────────────────────────────
Route::middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {
 
        // Stats globales + charts
        Route::get('/stats',           [AdminController::class, 'stats']);
 
        // Activité récente
        Route::get('/activity',        [AdminController::class, 'recentActivity']);
 
        // Gestion utilisateurs
        Route::get('/users',           [AdminController::class, 'users']);
        Route::put('/users/{user}/suspend',  [AdminController::class, 'suspendUser']);
        Route::put('/users/{user}/activate', [AdminController::class, 'activateUser']);
        Route::delete('/users/{user}',       [AdminController::class, 'deleteUser']);
 
        // Gestion machines
        Route::get('/machines',            [AdminController::class, 'machines']);
        Route::delete('/machines/{machine}', [AdminController::class, 'deleteMachine']);
 
        // Gestion réservations
        Route::get('/reservations',    [AdminController::class, 'reservations']);
    });

    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/',           [ProfileController::class, 'show']);
    Route::put('/',           [ProfileController::class, 'update']);
    Route::put('/password',   [ProfileController::class, 'updatePassword']);
    Route::post('/avatar',    [ProfileController::class, 'uploadAvatar']);
});
 