<?php

namespace App\Http\Requests;

use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
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
        $userStatus = implode(',', (new Enum(UserStatusEnum::class))->values());

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required | email | unique:admins,email',
            'password' => 'required | min:6 | max:18',
            'status' => "required | in:$userStatus",
        ];
    }
}
