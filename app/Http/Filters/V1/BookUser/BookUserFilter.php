<?php

namespace App\Http\Filters\V1\BookUser;

use App\Http\Filters\BaseFilter;

class BookUserFilter extends BaseFilter {
    protected $allowColumns = [
        'userId' => ['eq'],
        'bookId' => ['eq'],
        'status' => ['eq'],
        'amount' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'payment' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'discount' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'unit' => ['eq', 'like'],
        'extraMoney' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'approvedAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'rejectedAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'canceledAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'paidAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'borrowedAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'estimatedReturnedAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'returnedAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'extraMoneyAt' => ['eq', 'gt', 'lt', 'lte', 'gte'],
    ];

    protected $columnsMap = [
        'userId' => 'user_id',
        'bookId' => 'book_id',
        'extraMoney' => 'extra_money',
        'approvedAt' => 'approved_at',
        'rejectedAt' => 'rejected_at',
        'canceledAt' => 'canceled_at',
        'paidAt' => 'paid_at',
        'borrowedAt' => 'borrowed_at',
        'estimatedReturnedAt' => 'estimated_returned_at',
        'returnedAt' => 'returned_at',
        'extraMoneyAt' => 'extra_money_at',
    ];
}
