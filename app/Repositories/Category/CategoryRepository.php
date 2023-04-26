<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements ICategoryRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return Category::class;
    }
}
