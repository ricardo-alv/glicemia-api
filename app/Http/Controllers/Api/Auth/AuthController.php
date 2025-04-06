<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginUser;
use App\Http\Resources\UserResource;
use App\Mail\SendLinkResetPasswordEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(LoginUser $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' =>  trans('messages.invalid_credentials')
            ], 404);
        }

        $user->tokens()->delete();
        $token = $user->createToken('token_user')->plainTextToken;
        $user->token = $token;

        return new UserResource($user);
    }

    public function updatePasswordUser(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([], 204);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return new UserResource($user);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([], 204);
    }
}
