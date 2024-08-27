<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassword;
use App\Http\Services\PasswordService;


class PasswordController extends Controller
{
    public function __construct( private PasswordService $service)
      {

      }
    public function ChangeUserPassword(ChangePassword $request)
    {
return $this->service->changePassword($request->validated());
    }


}
