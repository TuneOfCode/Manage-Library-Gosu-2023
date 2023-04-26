<?php

namespace App\Http\Filters\V1\Book;

use Illuminate\Http\Request;
use App\Http\Filters\BaseFilter;

class BookFilter extends BaseFilter {
    protected $allowColumns = [
        'name' => ['eq', 'gt', 'like'],
        'categoryId' => ['eq'],
        'description' => ['eq', 'like'],
        'position' => ['eq', 'like'],
        'quantity' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'price' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'loanPrice' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'status' => ['eq'],
        'author' => ['eq', 'like']
    ];

    protected $columnsMap = [
        'categoryId' => 'category_id',
        'loanPrice' => 'loan_price',
        'publishedAt' => 'published_at',
    ];
}
