<?php

namespace App\Http\Filters\V1\User;

use App\Http\Filters\BaseFilter;

class UserFilter extends BaseFilter {
    /**
     * Định nghĩa thuộc tính cho phép các trường và phép lọc
     */
    protected $allowColumns = [
        'fullName' => ['eq', 'like'],
        'email' => ['eq', 'like'],
        'phone' => ['eq', 'like'],
        'address' => ['eq', 'like'],
        'score' => ['eq', 'lt', 'lte', 'gt', 'gte', 'neq'],
    ];
    /**
     * Định nghĩa thuộc tính ánh xạ với các trường trong CSDL
     */
    protected $columnsMap = [
        'fullName' => 'full_name',
    ];
}
