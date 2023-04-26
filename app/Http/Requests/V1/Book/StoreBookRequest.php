<?php

namespace App\Http\Requests\V1\Book;

use App\Enums\LabelBook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest {
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
            'name' => ['required', 'string'],
            'label' => ['required', 'string', Rule::in(LabelBook::MAP_VALUE)],
            'description' => ['sometimes', 'string'],
            'position' => ['required', 'string'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'loanPrice' => ['required', 'integer'],
            'author' => ['required', 'string'],
            'publishedAt' => ['required', 'string'],
            'categoryId' => ['required', 'integer', 'exists:categories,id'],
        ];
    }
    /**
     * Ánh xạ tên cột trong database
     */
    public function prepareForValidation() {
        $this->merge(
            [
                'loan_price' => $this->loanPrice,
                'published_at' => $this->publishedAt,
                'category_id' => $this->categoryId
            ]
        );
    }
}
