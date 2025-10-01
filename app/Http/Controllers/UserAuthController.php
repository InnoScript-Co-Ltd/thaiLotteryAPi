<?php

namespace App\Http\Controllers;

use App\Enums\UserStatusEnum;
use App\Http\Requests\AuthUserUpdateRequest;
use App\Http\Requests\UserAuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserAuthController extends Controller
{
    protected function respondWithToken($token)
    {
        return $this->success('user is logged in successfully', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => auth::guard('api')->user(),
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

            $token = Auth::guard('api')->attempt($payload->toArray());

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

    public function show()
    {
        try {
            $user = auth::guard('api')->user();

            return $this->success($user, 'Logged in user is retrived successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function update(AuthUserUpdateRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = auth::guard('api')->user();
            $user->update($payload->toArray());

            DB::commit();

            return $this->success($payload, 'Logged in user is updated successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }
}
