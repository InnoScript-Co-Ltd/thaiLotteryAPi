<?php

namespace App\Http\Controllers;

use App\Enums\UserStatusEnum;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AuthAdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    protected function respondWithToken($token)
    {
        return $this->success('admin is logged in successfully', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60,
            'user' => auth::guard('admin')->user(),
        ]);
    }

    public function Login(AdminLoginRequest $request)
    {
        $payload = collect($request->validated());

        try {
            $admin = Admin::where(['email' => $payload['email']])->first();

            if ($admin === null) {
                return $this->notFound('admin does not exist');
            }

            $token = Auth::guard('admin')->attempt($payload->toArray());

            switch ($admin['status']) {
                case UserStatusEnum::PENDING->value === $admin['status']:
                    return $this->badRequest('admin is not verified');

                case UserStatusEnum::BLOCK->value === $admin['status']:
                    return $this->badRequest('admin is not available');

                default:
                    if (! $token) {
                        return $this->unauthenticated('email or password is not match');
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
            $admin = auth::guard('admin')->user();

            return $this->success($admin, 'Logged in admin is retrived successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function update(AuthAdminUpdateRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $admin = auth::guard('admin')->user();
            $admin->update($payload->toArray());

            DB::commit();

            return $this->success($payload, 'Logged in admin is updated successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }
}
