<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest {
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
            'email' => 'required|email|exists:users,email',
            'otpEmailCode' => 'required|string|max:6'
        ];
    }
    /**
     * Ánh xạ các trường dữ liệu trong cơ sở dữ liệu
     */
    public function prepareForValidation(): void {
        $this->merge([
            'otp_email_code' => $this->input('otpEmailCode')
        ]);
    }
}
