<?php

namespace App\Repositories\BookUser;

use App\Models\BookUser;
use App\Repositories\BaseRepository;

class BookUserRepository extends BaseRepository implements IBookUserRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return BookUser::class;
    }
}
