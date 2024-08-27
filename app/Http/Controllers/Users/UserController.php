<?php

namespace App\Http\Controllers\Users;

use App\Actions\ChangePassword;
use App\Actions\UpdateUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(): UserResource
    {
        return new UserResource(Auth::user());
    }

    public function update(UpdateUserRequest $request, UpdateUser $updateUser): UserResource
    {
        $updateUser(
            user: $request->user(),
            name: $request->input('name'),
            email: $request->input('email'),
        );

        return new UserResource(Auth::user()->fresh());
    }

    public function changePassword(ChangePasswordRequest $request, ChangePassword $changePassword): JsonResponse
    {
        $changePassword(
            user: $request->user(),
            password: $request->input('password')
        );

        return response()->json([
            'status' => 'password-changed',
        ]);
    }
}
