<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;

class TokenLoginController extends Controller
{
    use HttpResponses;
    /**
     * Handle an incoming registration request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error([], 'The provided credentials are incorrect.', 422);
        }

        $device = substr($request->userAgent() ?? '', 0, 255);

        return $this->success([
            'token' => $user->createToken($device)->plainTextToken,
            'user' => $user,
        ], 'User logged in successfully.');

    }
}