<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {   
        if (Auth::viaRemember()) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'data' => UserResource::make($user),
                'token' => $token,
            ]);
        }

        if (
            Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ], (bool) $request->input('remember_me', false))
        ) {
            $request->session()->regenerate();
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'data' => UserResource::make($user),
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Incorrect email or password.',
        ], 422);
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return Auth::logout();
    }

    public function getAuthenticatedUser()
    {
        if (Auth::check()) {
            return response()->json([
                'data' => UserResource::make(Auth::user()),
            ]);
        }

        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }
}
