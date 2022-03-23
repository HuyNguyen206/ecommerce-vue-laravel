<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateUserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('me');
    }

    public function me()
    {
        return new PrivateUserResource(\request()->user());
    }

    public function refresh()
    {
        return response()->json([
            'meta' => [
                'access_token' => auth()->refresh(),
            ]]);
    }

}
