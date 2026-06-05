<?php

use Illuminate\Support\Facades\Route;
/* ── PAGE D'ACCUEIL ── */
Route::get('/', fn() => view('welcome'));

/* ── CATALOGUE & MACHINES ── */
Route::get('/machines',              fn() => view('machines.index'));
Route::get('/machines/create',       fn() => view('machines.create'));
Route::get('/machines/{id}',         fn(int $id) => view('machines.show', ['id' => $id]))
     ->where('id', '[0-9]+');
Route::get('/machines/{id}/edit',    fn(int $id) => view('machines.create', ['id' => $id, 'editMode' => true]))
     ->where('id', '[0-9]+');




Route::get('/logout',   fn() => redirect('/'));   // Le vrai logout se fait via POST API

/* ── DASHBOARDS ── */
Route::get('/dashboard',         fn() => redirect('/'));       // redirige selon rôle (JS côté client)
Route::get('/dashboard/client',  fn() => view('dashboard.client'));
Route::get('/dashboard/owner',   fn() => view('dashboard.owner'));

/* ── FALLBACK 404 ── */
Route::fallback(function() {
    abort(404, 'Page introuvable');
});


// Page Contact
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function (Illuminate\Http\Request $request) {
         return back()->with('success', 'Votre message a été envoyé avec succès !');
});


// Dashboard Admin
Route::get('/dashboard/admin', function () {
    return view('admin.dashboard');
})->name('dashboard.admin');

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile');

Route::get('/favoris', fn() => view('client.favorites'));

Route::get('/login', fn() => view('auth.auth_sliding'));
Route::get('/register', fn() => view('auth.auth_sliding'));

Route::get('/auth/google',          [App\Http\Controllers\Auth\GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback']);
Route::get('/auth/google/success', fn() => view('auth.google_success'));