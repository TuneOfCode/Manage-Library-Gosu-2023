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

    public function getTypeBook(string $type = 'desc') {
        // return $this->getModel()::latest()
        //     ->whereDate('created_at', '>=', now()->subDays(30)->toDateString())
        //     ->get();
        return $this->getModel()::orderBy('published_at', $type)->get();
    }
}