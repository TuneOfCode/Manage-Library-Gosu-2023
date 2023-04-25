<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class CategoryFilter extends ApiFilter{
    protected $allowedParams=[
        'name' => ['eq', 'gt', 'like'],
    ];

    protected $columnMap = [
    ];
}