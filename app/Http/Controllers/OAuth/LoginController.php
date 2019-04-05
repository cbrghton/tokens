<?php

namespace App\Http\Controllers\OAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function generateToken(Request $request)
    {
        $request->validate([
            'username' => 'required|alpha_dash|max:20',
            'password' => 'required|string|max:50'
        ]);

        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'status' => 'A'
        ];

        if(!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer'
        ]);
    }
}
