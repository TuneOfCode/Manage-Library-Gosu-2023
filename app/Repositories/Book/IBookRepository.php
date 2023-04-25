<?php 

namespace App\Repositories\Book;
use App\Repositories\IBaseRepository;

interface IBookRepository extends IBaseRepository {
    /**
     * Lấy ra tất cả sách mới
     */
    public function getTypeBook(string $type = 'desc');
}