<?php

namespace App\Http\Requests;

use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = User::findOrFail(request('id'))->id;
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
