<?php

namespace App\Http\Requests\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
        return [
        'name'=>['required', 'string'],
        'image' => 'required|mimes:jpeg,png,bmp',
        'quantity'=>['required', 'integer'],
        'price'=>['required', 'integer'],
        'loanPrice'=>['required', 'integer'],
        'status'=>['sometimes','boolean'],
        'author'=>['required','string'],
        'publishedAt'=>['required', 'string'],
        'categoryId' =>['required', 'integer'],
        ];
    }
    protected function prepareForValidation(){
        $this->merge(
            [
                'loan_price'=>$this->loanPrice,
                'published_at'=>$this->publishedAt,
                'category_id' => $this->categoryId
            ]
            );
    }   
}
