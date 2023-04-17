<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'oldPassword' => 'required|string|min:8',
            'newPassword' => 'required|string|min:8|different:oldPassword',
            'confirmNewPassword' => 'required|string|min:8|same:newPassword',
        ];
    }
}
