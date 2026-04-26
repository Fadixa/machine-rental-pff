<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:owner,client',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user  = User::create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'         => $user,
            'token'        => $token,
            'token_type'   => 'Bearer',
        ], 201);
    }

  public function login(Request $request)
{
  

    if (! Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }


    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user, 
        'token' => $token, 
        'token_type' => 'Bearer'
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('machines', 'reservations'));
    }
}