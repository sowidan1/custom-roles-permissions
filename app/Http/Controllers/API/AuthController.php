<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;
use App\Traits\ApiFormatResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiFormatResponse;
    public function register(RegisterRequest $request) {
        $validator = $request->validated();
        $user = new User;
        $user->name = $validator['name'];
        $user->email = $validator['email'];
        $user->role = $validator['role'];
        $user->password = Hash::make($validator['password']);
        $user->save();

        return $this->respondSuccess($user, 'Register Success');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (! $token = auth('api')->attempt($credentials)) {
            return $this->respondError('Unauthorized', 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return $this->respondSuccess(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return $this->respondSuccess();
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
