<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self NORMAL()
 * @method static self VIP()
 * @method static self HOT()
 * @method static self TRENDING()
 * @method static self BESTBORROW()
 */
class BookLabel extends Enum {
    const MAP_VALUE = [
        'NORMAL' => 'normal',
        'VIP' => 'vip',
        'HOT' => 'hot',
        'TRENDING' => 'trending',
        'BESTBORROW' => 'bestborrow'
    ];
}
