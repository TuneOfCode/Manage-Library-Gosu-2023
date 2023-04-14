<?php 

namespace App\Repositories\Book;
use App\Models\Book;
use App\Repositories\BaseRepository;

class BookRepository extends BaseRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return Book::class;
    }
}