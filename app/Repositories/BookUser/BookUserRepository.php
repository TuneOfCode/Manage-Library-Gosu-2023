<?php

namespace App\Repositories\BookUser;

use App\Enums\RentBookStatus;
use App\Models\BookUser;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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
    /**
     * Lấy ra số lượng lịch sử thuê sách với trạng thái hiện tại:
     * + sách trả quá hạn
     * + không trả sách
     */
    public function getAllHistoryWithOverdueAndNotReturn(string $id, string $userId) {
        return $this->_model
            ->where(
                [
                    ['id', '=', $id],
                    ['user_id', '=', $userId],
                    ['status', '=', str_replace(
                        '_',
                        ' ',
                        strtolower(RentBookStatus::OVERDUE_RETURNED()->value)
                    )]
                ],
            )
            ->orWhere([
                ['id', '=', $id],
                ['user_id', '=', $userId],
                ['status', '=', str_replace(
                    '_',
                    ' ',
                    strtolower(RentBookStatus::NOT_RETURNED()->value)
                )]
            ],)
            ->get();
    }
}
