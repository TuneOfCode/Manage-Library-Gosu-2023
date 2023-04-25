<?php

namespace App\Http\Requests\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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

        if($method == 'PUT'){
            return [
            'name'=>['required'],
            'image' => 'required|mimes:jpeg,png,bmp',
            'quantity'=>['required'],
            'price'=>['required'],
            'loanPrice'=>['required'],
            'status'=>['required'],
            'author'=>['required'],
            'publishedAt'=>['required'],
            'categoryId' =>['required'],
            'id' => ['required', 'integer', 'exists:books,id'],
            ];
        }else{
            return [
                'name'=>['sometimes','required'],
                'image' => 'sometimes|mimes:jpeg,png,bmp',
                'quantity'=>['sometimes','required'],
                'price'=>['sometimes','required'],
                'loanPrice'=>['sometimes','required'],
                'status'=>['sometimes','required'],
                'author'=>['sometimes','required'],
                'publishedAt'=>['sometimes','required'],
                'categoryId' =>['sometimes','required'],
                'id' => ['sometimes', 'integer', 'exists:books,id'],
                ];
        }
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
