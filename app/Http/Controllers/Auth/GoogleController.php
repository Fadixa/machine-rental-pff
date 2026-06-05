<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (\Exception $e) {
        \Log::error('Google OAuth error: ' . $e->getMessage());
        return redirect('/login?error=google_failed&msg=' . urlencode($e->getMessage()));
    }

    $user = User::where('email', $googleUser->getEmail())->first();

    if (!$user) {
        $user = User::create([
            'name'     => $googleUser->getName(),
            'email'    => $googleUser->getEmail(),
            'password' => bcrypt(\Illuminate\Support\Str::random(24)),
            'role'     => 'client',
            'profile_photo_path' => $googleUser->getAvatar(),
        ]);
    }

    if ($user->is_suspended) {
        return redirect('/login?error=suspended');
    }

    $token = $user->createToken('google-auth')->plainTextToken;

    return redirect('/auth/google/success?token=' . $token . '&user=' . urlencode(json_encode([
        'id'    => $user->id,
        'name'  => $user->name,
        'email' => $user->email,
        'role'  => $user->role,
        'profile_photo_path' => $user->profile_photo_path,
    ])));
}
}