<?php

namespace App\Repositories\Package;

use App\Models\Package;
use App\Repositories\BaseRepository;

class PackageRepository extends BaseRepository implements IPackageRepository {
    /**
     * Cài đặt hàm trừu tượng
     */
    public function getModel() {
        return Package::class;
    }
}
