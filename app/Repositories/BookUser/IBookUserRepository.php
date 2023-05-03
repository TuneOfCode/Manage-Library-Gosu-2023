<?php

namespace App\Repositories\BookUser;

use App\Repositories\IBaseRepository;

interface IBookUserRepository extends IBaseRepository {
    /**
     * Đếm số lượng sách đã mượn của 1 thành viên
     * với trạng thái đang mượn sách
     */
    public function countBorrowedBooks($userId);
}
