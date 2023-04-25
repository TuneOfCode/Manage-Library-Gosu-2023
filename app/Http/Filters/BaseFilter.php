<?php

namespace App\Http\Filters;

use Illuminate\Http\Request;

abstract class BaseFilter {
    /**
     * Định nghĩa thuộc tính cho phép các trường và phép lọc
     */
    protected $allowColumns = [];
    /**
     * Định nghĩa thuộc tính ánh xạ với các trường trong CSDL
     */
    protected $columnsMap = [];
    /**
     * Định nghĩa phép toán so sánh trong CSDL
     */
    protected $operatorsMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'neq' => '!=',
        'like' => 'like',
    ];
    /**
     * URL: column[operator]=value
     * Eloquent: [column, operator, value]
     * Hàm chuyển từ query trong url sang query trong eloquent
     * @param Request $request
     */
    public function transform(Request $request) {
        $eloquentQuery = [];
        $eloquentQueryNotOperators = [];

        foreach ($this->allowColumns as $fields => $operators) {
            $queryValue = $request->query($fields);

            if (!isset($queryValue)) {
                continue;
            }

            $column = $this->columnsMap[$fields] ?? $fields;

            foreach ($operators as $operator) {
                // Nếu phép toán không tồn tại trong mảng phép toán 
                // thì mặc định là = 
                if (isset($queryValue[$operator])) {
                    if ($this->operatorsMap[$operator] === 'like') {
                        $eloquentQuery[] = [$column, $this->operatorsMap[$operator],  '%' . $queryValue[$operator] . '%'];
                    } else {
                        $eloquentQuery[] = [$column, $this->operatorsMap[$operator], $queryValue[$operator]];
                    }
                } else {
                    $eloquentQueryNotOperators[] = [$column, '=', $queryValue];
                }
            }
        }

        return count($eloquentQuery) > 0
            ? $eloquentQuery
            : array_unique(array_merge($eloquentQuery, $eloquentQueryNotOperators), SORT_REGULAR);
    }
    /**
     * Hàm xác định query có mối quan hệ và lấy ra danh sách mối quan hệ
     */
    public function getRelations(Request $request) {
        $relationsRequest = $request->get('relations');
        $relations = !isset($relationsRequest)
            ? []
            : explode(', ', ($request->get('relations')));
        foreach ($relations as $key => $relation) {
            $relations[$key] = trim($relation);
        }
        return $relations;
    }
}
