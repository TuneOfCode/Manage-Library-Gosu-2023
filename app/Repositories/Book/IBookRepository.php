<?php

namespace App\Repositories\Book;

use App\Repositories\IBaseRepository;

interface IBookRepository extends IBaseRepository {
    /**
     * Lấy ra những cuốn sách theo yêu cầu
     */
    public function getAttributesBooks(mixed $attributes);
}
