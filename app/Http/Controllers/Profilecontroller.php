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
        $user = $request->user();

        // ✅ FIX : retourner data directement (profile/index.blade attend json.data)
        return response()->json([
            'data' => [
                'id'                  => $user->id,
                'name'                => $user->name,
                'email'               => $user->email,
                'phone'               => $user->phone,
                'city'                => $user->city,
                'bio'                 => $user->bio,
                'role'                => $user->role,
                'profile_photo_path'  => $user->profile_photo_path,
            ],
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

        // ✅ FIX : retourner data (cohérent avec show())
        return response()->json([
            'data' => [
                'id'                  => $user->id,
                'name'                => $user->name,
                'email'               => $user->email,
                'phone'               => $user->phone,
                'city'                => $user->city,
                'bio'                 => $user->bio,
                'role'                => $user->role,
                'profile_photo_path'  => $user->profile_photo_path,
            ],
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

    // ─── POST /api/profile/avatar ─────────────────────────────────
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = $request->user();

        // ✅ Supprimer l'ancien avatar si existant
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['profile_photo_path' => $path]);

        // ✅ FIX : retourner data.profile_photo_path (attendu par le front)
        return response()->json([
            'data' => [
                'profile_photo_path' => $path,
            ],
            'message' => 'Photo de profil mise à jour.',
        ]);
    }
}