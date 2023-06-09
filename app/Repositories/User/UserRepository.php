<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements IUserRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return User::class;
    }
}
