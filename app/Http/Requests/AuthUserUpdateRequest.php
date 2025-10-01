<?php

namespace App\Http\Requests;

use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuthUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth::guard('api')->user()->id;
        $userStatus = implode(',', (new Enum(UserStatusEnum::class))->values());

        return [
            'first_name' => 'nullable | string',
            'last_name' => 'nullable | string',
            'phonr' => "nullable | unique:users,phone,$userId",
            'gender' => 'nullable | string',
            'address' => 'nullable | string',
            'nrc' => "nullable | string | unique:users,nrc,$userId",
            'password' => 'nullable | string | min:6 | max:18',
            'status' => "nullable | string | in:$userStatus",
        ];
    }
}
