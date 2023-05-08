<?php

namespace App\Constants;

use Spatie\Permission\Models\Permission;

/**
 * Class quản lý các quyền hệ thống 
 */
class PermissionConstant {
    /**
     * Quyền quản lý tài nguyên người dùng hệ thống
     */
    static string $CREATE_USER = 'create-user';
    static string $READ_ALL_USER = 'read-all-user';
    static string $READ_A_USER = 'read-a-user';
    static string $UPDATE_USER = 'update-user';
    static string $DELETE_USER = 'delete-user';
    static string $LOCK_USER = 'lock-user';
    static string $UNLOCK_USER = 'unlock-user';
    /**
     * Quyền quản lý tài nguyên gói ưu đãi thành viên
     */
    static string $CREATE_PACKAGE = 'create-package';
    static string $READ_ALL_PACKAGE = 'read-all-package';
    static string $READ_A_PACKAGE = 'read-a-package';
    static string $UPDATE_PACKAGE = 'update-package';
    static string $DELETE_PACKAGE = 'delete-package';
    static string $REGISTER_PACKAGE = 'register-package';
    static string $UNREGISTER_PACKAGE = 'unregister-package';
    static string $EXTEND_PACKAGE = 'extend-package';
    /**
     * Quyền quản lý tài nguyên loại sách
     */
    static string $CREATE_CATEGORY_BOOK = 'create-category-book';
    static string $READ_ALL_CATEGORY_BOOK = 'read-all-category-book';
    static string $READ_A_CATEGORY_BOOK = 'read-a-category-book';
    static string $UPDATE_CATEGORY_BOOK = 'update-category-book';
    static string $DELETE_CATEGORY_BOOK = 'delete-category-book';
    /**
     * Quyền quản lý tài nguyên sách
     */
    static string $CREATE_BOOK = 'create-book';
    static string $READ_ALL_BOOK = 'read-all-book';
    static string $READ_A_BOOK = 'read-a-book';
    static string $UPDATE_BOOK = 'update-book';
    static string $DELETE_BOOK = 'delete-book';
    static string $BORROW_BOOK = 'borrow-book';
    static string $CONFIRM_RETURNED_BOOK = 'confirm-returned-book';
    static string $APPROVE_BOOK = 'approve-book';
    static string $CANCEL_BOOK = 'cancel-book';
    static string $REJECT_BOOK = 'reject-book';
    static string $PAY_MONEY = 'pay-money';
    static string $CONFIRM_RECEIVED_BOOK = 'confirm-received-book';
    /**
     * Quyền quản lý tài nguyên thống kê
     */
    static string $READ_STATISTIC = 'read-statistic';
    static string $CREATE_STATISTIC = 'create-statistic';
    static string $UPDATE_STATISTIC = 'update-statistic';
    static string $DELETE_STATISTIC = 'delete-statistic';
}
