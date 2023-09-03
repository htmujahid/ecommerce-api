<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class TokenLogoutController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function __invoke(User $user): void
    {
        $user->tokens()->delete();
    }
}