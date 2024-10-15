<?php

namespace App\Services;

use App\Contracts\AuthInterface;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthInterface
{
    public function id()
    {
        return Auth::id();
    }
}