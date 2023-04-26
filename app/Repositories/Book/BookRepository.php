<?php

namespace App\Repositories\Book;

use App\Models\Book;
use App\Repositories\BaseRepository;

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
        $query = $attributes['query'] ?? [];
        $relations = $attributes['relations'] ?? [];
        $limit = $attributes['limit'] ?? 10;
        $pazeSize = $attributes['pazeSize'] ?? 10;
        $column = $attributes['column'] ?? 'created_at';
        $sortType = $attributes['sortType'] ?? 'desc';

        return $this->_model::where($query)
            ->with($relations)
            ->orderBy($column, $sortType)
            ->limit($limit)
            ->paginate($pazeSize);
    }
}
