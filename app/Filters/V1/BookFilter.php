<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class BookFilter extends ApiFilter{
    protected $allowedParams=[
        'name' => ['eq', 'gt', 'like'],
        'categoryId' => ['eq','lte'],
        'quantity' => ['eq','gt','lt'],
        'price' => ['eq','gt','lt'],
        'loanPrice' => ['eq','gt','lt'],
        'status' => ['eq'],
        'author' => ['eq'],
        'publishedAt' => ['eq'],
    ];

    protected $columnMap = [
        'categoryId' => 'category_id',
        'loanPrice' => 'loan_price',
        'publishedAt' => 'published_at',
    ];
}