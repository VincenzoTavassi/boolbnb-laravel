<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request, User $user)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $authenticatedUser = $user->find(Auth::id());
            $token = $authenticatedUser->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => $authenticatedUser,
                'token' => $token
            ],);
        } else {
            return response()->json(['error' => 'Credenziali non valide'], 401);
        }
    }
}
