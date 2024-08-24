<?php

namespace App\Http\Services;

use App\Models\EmailVerificationToken;
use App\Notifications\EmailVerificationNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Models\User;

class EmailVerificationService
{
    /**
     * Send verification link to a user
     */

    public function sendVerificationLink(object $user): void
    {
        Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));
    }

    /**
     * Resend link token
     */

    public function resendLink($email): JsonResponse
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $this->sendVerificationLink($user);
            return response()->json([
                'status' => 'success',
                'message' => 'Verification link has been sent'
            ]);
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'User not found'
        ], 404);
    }


    /**
     * Check verified email
     */
    public function checkIfEmailIsVerified($user)
    {
        if ($user->email_verified_at) {
            response()->json([
                'status' => 'failed',
                'message' => 'Email is already verified',
            ])->send();
            exit();
        }
    }

    /**
     * Verify user Email
     */

    public function verifyEmail(string $email, string $token)
    {
        try {
            $user = User::where('email', $email)->firstOrFail();

            $this->checkIfEmailIsVerified($user);

            $tokenRecord = $this->verifyToken($email, $token);
            if ($tokenRecord instanceof JsonResponse) {
                return $tokenRecord; // Возврат ошибки из метода verifyToken
            }

            $user->markEmailAsVerified();

            return response()->json(['status' => 'success', 'message' => 'Email has been verified successfully']);
        } catch (Exception) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid verification request'], 404);
        }
    }

    /**
     * Verify token
     */

    public function verifyToken(string $email, string $token)
    {
        $tokenRecord = EmailVerificationToken::where('email', $email)->where('token', $token)->first();

        if (!$tokenRecord || $tokenRecord->expired_at < now()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid or expired token.'
            ], 400);
        }

        $tokenRecord->delete();

        return $tokenRecord;
    }


    /**
     * Generate verification link
     */

    public function generateVerificationLink(string $email): string
    {
        $checkIfTokenExists = EmailVerificationToken::where('email', $email)->first();
        if ($checkIfTokenExists) $checkIfTokenExists->delete();
        $token = Str::uuid();
        $url = config('app.url') . "?token=" . $token . "&email=" . $email;
        $saveToken = EmailVerificationToken::create([
            "email" => $email,
            "token" => $token,
            "expired_at" => now()->addMinutes(60),
        ]);
        if ($saveToken) {
            return $url;
        }
        return response()->json('Failed to generate verification link.');
    }
}
