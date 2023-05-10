<?php

namespace App\Http\Filters\V1\Package;

use App\Http\Filters\BaseFilter;

class PackageFilter extends BaseFilter {
    /**
     * Định nghĩa thuộc tính cho phép các trường và phép lọc
     */
    protected $allowColumns = [
        'name' => ['eq', 'like'],
        'type' => ['eq'],
        'price' => ['eq', 'lt', 'gt', 'lte', 'gte'],
        'isActive' => ['eq', 'neq'],
    ];
    /**
     * Định nghĩa thuộc tính ánh xạ với các trường trong CSDL
     */
    protected $columnsMap = [
        'isActive' => 'is_active',
    ];
}
