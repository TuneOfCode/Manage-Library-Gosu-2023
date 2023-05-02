<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self NORMAL()
 * @method static self BROZEN()
 * @method static self SILVER()
 * @method static self GOLD()
 */
class PackageType extends Enum {
    const MAP_VALUE = [
        'NORMAL' => 'normal',
        'BROZEN' => 'brozen',
        'SILVER' => 'silver',
        'GOLD' => 'gold',
    ];
}
