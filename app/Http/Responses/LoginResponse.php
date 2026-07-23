<?php

namespace App\Http\Responses;
use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->isAdminSide()) {
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->intended(route('user-dashboard'));
    }
}