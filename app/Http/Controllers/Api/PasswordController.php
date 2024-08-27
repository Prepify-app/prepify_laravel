<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Services\PasswordService;
use Illuminate\Http\Request;

class PasswordController extends Controller
{

    public function __construct(private PasswordService $service)
    {
    }

    public function changeUserPassword(ChangePasswordRequest $request)
    {
        return $this->service->changePassword($request->validated());
    }
}
