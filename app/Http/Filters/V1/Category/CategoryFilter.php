<?php

namespace App\Http\Filters\V1\Category;

use App\Http\Filters\BaseFilter;

class CategoryFilter extends BaseFilter {
    /**
     * Định nghĩa thuộc tính cho phép các trường và phép lọc
     */
    protected $allowColumns = [
        'name' => ['eq', 'like']
    ];
}
