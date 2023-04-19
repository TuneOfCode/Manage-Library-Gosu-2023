<?php

namespace App\Http\Requests\V1\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'POT'){
        return [
            'name' => 'required|string',
            'price'=>'required|integer'
        ];
        }else{
            return [
                'name' => ['sometimes','required|string'],
                'price' => ['sometimes','required|integer']    
            ];
        }
    }
}
