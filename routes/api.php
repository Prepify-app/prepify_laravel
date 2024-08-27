<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Profile\PasswordController;
use App\Http\Controllers\Api\Profile\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('auth/resend_email_verification_link', [AuthController::class, 'resendEmailVerificationLink']);

Route::post('password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('password/reset/password', [PasswordResetController::class, 'resetPassword']); // высылается токен с password/forgot, вставляешь его и пароль сменён

Route::middleware(['auth:api'])->group(function () {
    Route::post('/change_password', [PasswordController::class, 'ChangeUserPassword']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
