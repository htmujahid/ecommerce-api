<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenRegisterController extends Controller
{
    use HttpResponses;
    /**
     * Handle an incoming registration request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('customer');

        event(new Registered($user));

        $device = substr($request->userAgent() ?? '', 0, 255);

        return $this->success([
            'token' => $user->createToken($device)->plainTextToken,
            'user' => $user,
        ], 'User registered successfully.');
    }
}