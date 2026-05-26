<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ─── GET /api/profile ─────────────────────────────────────────
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'reservations' => fn($q) => $q->latest()->take(5)->with('machine:id,name,city,daily_price'),
            'machines'     => fn($q) => $q->latest()->take(5)->withCount('reservations'),
        ]);

        $stats = [];

        if ($user->role === 'client') {
            $stats = [
                'total_reservations' => $user->reservations()->count(),
                'completed'          => $user->reservations()->where('status', 'completed')->count(),
                'pending'            => $user->reservations()->where('status', 'pending')->count(),
                'total_spent'        => $user->reservations()
                    ->where('status', 'completed')
                    ->join('machines', 'reservations.machine_id', '=', 'machines.id')
                    ->selectRaw('SUM(DATEDIFF(reservations.end_date, reservations.start_date) * machines.daily_price) as total')
                    ->value('total') ?? 0,
            ];
        }

        if ($user->role === 'owner') {
            $stats = [
                'total_machines'     => $user->machines()->count(),
                'available_machines' => $user->machines()->where('status', 'available')->count(),
                'total_reservations' => \App\Models\Reservation::whereHas('machine', fn($q) => $q->where('owner_id', $user->id))->count(),
                'total_revenue'      => \App\Models\Reservation::whereHas('machine', fn($q) => $q->where('owner_id', $user->id))
                    ->where('status', 'completed')
                    ->join('machines', 'reservations.machine_id', '=', 'machines.id')
                    ->selectRaw('SUM(DATEDIFF(reservations.end_date, reservations.start_date) * machines.daily_price) as total')
                    ->value('total') ?? 0,
            ];
        }

        return response()->json([
            'user'  => $user,
            'stats' => $stats,
        ]);
    }

    // ─── PUT /api/profile ─────────────────────────────────────────
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'city'  => 'nullable|string|max:100',
            'bio'   => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user'    => $user->fresh(),
        ]);
    }

    // ─── PUT /api/profile/password ────────────────────────────────
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Mot de passe modifié avec succès.']);
    }

    // ─── POST /api/profile/avatar ────────────────────────────────
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();

        // ✅ FIX : Supprimer l'ancien avatar avec la bonne colonne profile_photo_path
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        
        // ✅ FIX : Update profile_photo_path au lieu de avatar
        $user->update(['profile_photo_path' => $path]);

        return response()->json([
            'message'    => 'Photo de profil mise à jour.',
            'avatar_url' => Storage::url($path),
            'user'       => $user->fresh() // On retourne le user frais
        ]);
    }
}