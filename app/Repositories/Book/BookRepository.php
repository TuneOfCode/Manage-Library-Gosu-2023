<?php

namespace App\Repositories\Book;

use App\Models\Book;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Schema;

class BookRepository extends BaseRepository implements IBookRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return Book::class;
    }
    /**
     * Lấy ra những cuốn sách theo yêu cầu
     */
    public function getAttributesBooks(mixed $attributes) {
        $columns = Schema::getColumnListing($this->_model->getTable());
        // kiểm tra xem có tồn tại cột đó hay không
        foreach ($columns as $column) {
            if ($column !== $attributes['column']) {
                $attributes['column'] = 'created_at';
                break;
            }
        }
        $query = $attributes['query'] ?? [];
        $relations = $attributes['relations'] ?? [];
        $limit = $attributes['limit'] ?? 10;
        $pageSize = $attributes['pageSize'] ?? 10;
        $column = $attributes['column'] ?? 'created_at';
        $sortType = $attributes['sortType'] ?? 'desc';

        return $this->_model::where($query)
            ->with($relations)
            ->orderBy($column, $sortType)
            ->limit($limit)
            ->paginate($pageSize);
    }
}
