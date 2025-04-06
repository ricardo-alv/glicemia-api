<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendLinkResetPasswordEmail;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function __construct(
        protected  UserService $userService
    ) {}

    public function sendLinkResetPassword(Request $request)
    {
        return $this->userService->sendLinkResetPassword($request);
    }

    public function update(Request $request)
    {        
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);
        
        return $this->userService->passwordUpdate($request);
    }
}
