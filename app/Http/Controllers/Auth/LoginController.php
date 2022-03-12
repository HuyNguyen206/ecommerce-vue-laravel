<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateUserResource;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'errors' => [
                    'email' => ['Could not sign you in with these details']
                ]
            ], 422);
        }
        return  PrivateUserResource::make($request->user())->additional([
           'meta' => [
               'access_token' => $token,
           ]
        ]);
    }
}
