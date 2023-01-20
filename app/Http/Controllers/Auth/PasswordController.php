<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [function ($attribute, $value, $fail) {
                if (!(Hash::check($value, auth()->user()->password)))
                    $fail('Your current password did not match');
            }, 'required'],
            'new_password' => ['required', 'min:8', 'max:50', 'same:password_confirmation'],
            'password_confirmation' => 'required|string|min:8|max:50',
        ]);

        $user = auth()->user();
        try {
            $user->update(['password' => bcrypt($validated['new_password'])]);
            return response(['message' => 'Password changed successfully !'], 200);
        } catch (\Throwable $th) {
            return response(['message' => 'Something went wrong'], 500);
        }
    }
}
