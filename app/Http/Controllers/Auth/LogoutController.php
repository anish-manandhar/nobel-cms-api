<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        return Auth::user()->tokens()->delete()
            ? response(['message' => 'Logged out succesfully'], 200)
            : response(['message' => 'Something went wrong'], 500);
    }
}
