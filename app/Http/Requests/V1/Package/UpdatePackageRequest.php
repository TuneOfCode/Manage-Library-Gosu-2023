<?php

namespace App\Http\Requests\V1\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest {
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
        if ($this->method() === 'PUT') {
            return [
                'name' => ['required', 'string', 'max:255', 'unique:packages'],
                'price' => ['required', 'numeric', 'min:0'],
                'description' => ['required', 'string', 'max:255'],
                'isActive' => ['required'],
            ];
        }
        return [
            'name' => ['sometimes', 'string', 'max:255', 'unique:packages'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['sometimes', 'string', 'max:255'],
            'isActive' => ['sometimes'],
        ];
    }
    /**
     * Ánh xạ trong CSDL
     */
    public function prepareForValidation() {
        $this->merge([
            'is_active' => $this->input('isActive'),
        ]);
    }
}
