<?php

namespace App\Http\Services;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Services\ResponseService;

class PasswordResetService
{
    public function sendResetLink(string $email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = $this->generateResetToken($email);
            Notification::send($user, new ForgetPasswordNotification($token));
            return response()->json([
                'status' => StatusService::SUCCESS,
                'message' => ResponseService::PASSWORD_RESET_LINK_SENT,
            ]);
        }
    }

    public function generateResetToken(string $email): string
    {
        $token = Str::uuid();
        PasswordResetToken::updateOrCreate(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        return $token;
    }

    public function resetPassword(string $email, string $token, string $password)
    {
        $tokenRecord = PasswordResetToken::where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$tokenRecord || $tokenRecord->created_at->addMinutes(60) < now()) {
            return response()->json([
                'status' => 'failed',
                'message' => ResponseService::INVALID_RESET_TOKEN
            ], 400);
        }

        $user = User::where('email', $email)->first();
        $user->password = bcrypt($password);
        $user->save();

        $tokenRecord->delete();

        return response()->json([
            'status' => StatusService::SUCCESS,
            'message' => ResponseService::PASSWORD_RESET_SUCCESS,
        ]);
    }
}
