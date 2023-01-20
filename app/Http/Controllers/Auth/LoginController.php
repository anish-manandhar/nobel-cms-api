<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ],
            [
                'email.exists' => "Email does not belongs to this system."
            ]
        );

        $user = User::where('email', $validated['email'])->firstOrFail();

        if (!Auth::attempt($request->only(['email', 'password'])))
            return response(['message' => 'Incorrect Password'], 403);

        $user->hasRole('Student') ? $user->load('students') : $user->load('employees_details');

        $token = $user->createToken('user-token')->plainTextToken;

        return response([
            'user' => UserResource::make($user),
            'token' => $token,
        ], 200);
    }
}
