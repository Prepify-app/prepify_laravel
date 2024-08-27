<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResendEmailVerificationLinkRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Services\EmailVerificationService;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(public EmailVerificationService $service)
    {

    }

    /**
     * Login method
     */

    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if ($token) {
            return $this->responseWithToken($token, auth()->user());
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    /**
     * Resend verification link
     */
    public function resendEmailVerificatioLink(ResendEmailVerificationLinkRequest $request)
    {
        Log::info('Resend email verification link request received', ['email' => $request->email]);
        return $this->service->resendLink($request->email);
    }

    /**
     * Registration method
     */

    public function register(RegistrationRequest $request)
    {
        $user = User::create($request->validated());
        if ($user) {
            $this->service->sendVerificationLink($user);
            $token = auth()->login($user);
            return $this->responseWithToken($token, $user);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong while trying to create user'
            ], 500);
        }

    }

    /**
     * Resend verification link
     */

    public function resendEmailVerificationLink(ResendEmailVerificationLinkRequest $request)
    {
        return $this->service->resendLink($request->email);
    }

    /**
     * Verify user Email
     */

    public function verifyUserEmail(VerifyEmailRequest $request)
    {
        return $this->service->verifyEmail($request->email, $request->token);
    }

    /**
     * Logout Method
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Return JWT access token
     */
    public function responseWithToken($token, $user)
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer',
            'expires_in' => Config::get('jwt.ttl') * 7 // 7 дней
        ]);
    }
}
