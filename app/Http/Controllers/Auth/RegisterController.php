<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return PrivateUserResource::make($user);
    }
}
