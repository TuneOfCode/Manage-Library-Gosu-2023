<?php

namespace App\Repositories\BookUser;

use App\Repositories\IBaseRepository;

interface IBookUserRepository extends IBaseRepository {
    /**
     * Đếm số lượng sách đã mượn của 1 thành viên
     * với trạng thái đang mượn sách
     */
    public function countBorrowedBooks($userId);
    /**
     * Lấy ra số lượng lịch sử thuê sách với trạng thái hiện tại:
     * + sách trả quá hạn
     * + không trả sách
     */
    public function getAllHistoryWithOverdueAndNotReturn(string $id, string $userId);
}
