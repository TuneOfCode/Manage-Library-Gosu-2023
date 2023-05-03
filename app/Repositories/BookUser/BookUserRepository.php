<?php

namespace App\Repositories\BookUser;

use App\Enums\RentBookStatus;
use App\Models\BookUser;
use App\Repositories\BaseRepository;

class BookUserRepository extends BaseRepository implements IBookUserRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return BookUser::class;
    }
    /**
     * Đếm số lượng sách đã mượn của 1 thành viên
     * với trạng thái đang mượn sách
     */
    public function countBorrowedBooks($userId) {
        return $this->_model->where([
            ['user_id', '=', $userId],
            ['status', '=', strtolower(RentBookStatus::BORROWING()->value)]
        ])->count();
    }
}
