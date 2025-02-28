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
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use App\Services\StatusService;

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
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => StatusService::FAILED,
                'message' => ResponseService::INVALID_CREDENTIALS,
            ], 401);
        }
        $token = auth()->attempt($request->validated());
        if ($token) {
            return $this->responseWithToken($token, auth()->user());
        } else {
            return response()->json([
                'status' => StatusService::FAILED,
                'message' => ResponseService::INVALID_CREDENTIALS,
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
        try {
            $user = User::create($request->validated());
            if ($user) {
                $this->service->sendVerificationLink($user);
                $token = auth()->login($user);
                return $this->responseWithToken($token, $user);
            } else {
                return response()->json([
                    'status' => StatusService::FAILED,
                    'message' => ResponseService::USER_CREATION_ERROR,
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => StatusService::FAILED,
                'message' => ResponseService::USER_CREATION_ERROR,
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

        return response()->json(['message' => ResponseService::LOGOUT_SUCCESS]);
    }

    /**
     * Return JWT access token
     */
    public function responseWithToken($token, $user)
    {
        return response()->json([
            'status' => StatusService::SUCCESS,
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer',
            'expires_in' => Config::get('jwt.ttl') * 7 // 7 дней
        ]);
    }
}
