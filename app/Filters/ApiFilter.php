<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter{
    /**
     * 
     */
    protected $allowedParams=[];
    /**
     * 
     */
    protected $columnMap = [];

    /**
     * Cho phép định nghĩa các toán tử tìm kiếm
     */
    protected $operatorsMap = [
        'eq' => '=',
        'ne' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'like' => 'like',
    ];
    /**
     * Chuyển các query từ request từ dạng thô 
     * về dạng mảng có thể sử dụng trong Eloquent
     * @param Request $request
     * @return array $eloquentFilter
     */
    public function transform(Request $request): array {
        $eloquentFilter = [];
        foreach ($this->allowedParams as $param => $operators) {
            $queryValue = $request->query($param);
            if (!isset($queryValue)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;
            foreach ($operators as $operator) {
                if (isset($queryValue[$operator])) {
                    $eloquentFilter[] = [$column, $this->operatorsMap[$operator], $queryValue[$operator]];
                }
            }
        }

        return $eloquentFilter;
    }
}