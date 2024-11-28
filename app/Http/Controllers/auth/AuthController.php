<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('', 'Credentials do not match', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken of ' . $user->name, ['*'], now()->addMinutes(value: 30))->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'access_token' => $token
        ], 'Logged in successfully', 200);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'role' => '0'
        ]);

        return $this->successResponse(null, 'User registered successfully', 201);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully', 200);
    }
}