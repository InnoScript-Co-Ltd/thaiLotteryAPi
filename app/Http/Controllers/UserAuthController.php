<?php

namespace App\Http\Controllers;

use App\Enums\UserStatusEnum;
use App\Http\Requests\UserAuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    protected function respondWithToken($token)
    {
        return $this->success('user is logged in successfully', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth::user(),
        ]);
    }

    public function Login(UserAuthRequest $request)
    {
        $payload = collect($request->validated());

        try {
            $user = User::where(['phone' => $payload['phone']])->first();

            if ($user === null) {
                return $this->notFound('user does not exist');
            }

            $token = Auth::attempt($payload->toArray());

            switch ($user['status']) {
                case UserStatusEnum::PENDING->value === $user['status']:
                    return $this->badRequest('user is not verified');

                case UserStatusEnum::BLOCK->value === $user['status']:
                    return $this->badRequest('user is not available');

                default:
                    if (! $token) {
                        return $this->unauthenticated('phone or password is not match');
                    }

                    return $this->respondWithToken($token);
            }
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }
}
