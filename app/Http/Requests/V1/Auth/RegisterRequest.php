<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
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
            'fullName' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users', 'email'],
            'username' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'confirmPassword' => ['sometimes', 'string', 'min:8', 'same:password']
        ];
    }
    /**
     * Ánh xạ các trường dữ liệu trong cơ sở dữ liệu
     */
    public function prepareForValidation(): void {
        $this->merge([
            'full_name' => $this->input('fullName')
        ]);
    }
}
