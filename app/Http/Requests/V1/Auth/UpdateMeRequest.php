<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeRequest extends FormRequest {
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
            'fullName' => 'sometimes|string|max:255',
            // 'email' => 'sometimes|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255'
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
