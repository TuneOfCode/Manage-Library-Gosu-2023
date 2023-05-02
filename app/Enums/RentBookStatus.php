<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self PENDING()
 * @method static self PAYING()
 * @method static self RECEIVING()
 * @method static self BORROWING()
 * @method static self RETURNED()
 * @method static self OVERDUE_RETURNED()
 * @method static self NOT_RETURNED()
 * @method static self CANCEL()
 * @method static self REFUSED()
 */
class RentBookStatus extends Enum {
    const MAP_VALUE = [
        'PENDING' => 'pending', // đang chờ duyệt
        'REJECTED' => 'rejected', // đã từ chối yêu cầu mượn sách
        'PAYING' => 'paying', // đang chờ thanh toán
        'RECEIVING' => 'receiving', // đang chờ nhận sách
        'BORROWING' => 'borrowing', // đang mượn sách
        'RETURNED' => 'returned', // đã trả sách
        'OVERDUE_RETURNED' => 'overdue returned', // trả sách quá hạn
        'NOT_RETURNED' => 'not returned', // không trả sách (mất sách, sách bị hư hỏng, ...)
        'CANCEL' => 'cancel', // đã hủy yêu cầu mượn sách
    ];
}
