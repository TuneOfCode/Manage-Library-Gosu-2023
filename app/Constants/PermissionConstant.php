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
    static string $RETURN_BOOK = 'return-book';
    static string $APPROVE_BOOK = 'approve-book';
    static string $REJECT_BOOK = 'reject-book';
    static string $PAY_MONEY = 'pay-money';
    /**
     * Quyền quản lý tài nguyên thống kê
     */
    static string $READ_STATISTIC = 'read-statistic';
    static string $CREATE_STATISTIC = 'create-statistic';
    static string $UPDATE_STATISTIC = 'update-statistic';
    static string $DELETE_STATISTIC = 'delete-statistic';
    /**
     * Hàm khởi tạo
     */
    public function __construct() {
        self::$CREATE_USER = Permission::create([
            'name' => 'create-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_ALL_USER = Permission::create([
            'name' => 'read-all-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_A_USER = Permission::create([
            'name' => 'read-a-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UPDATE_USER = Permission::create([
            'name' => 'update-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$DELETE_USER = Permission::create([
            'name' => 'delete-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$LOCK_USER = Permission::create([
            'name' => 'lock-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UNLOCK_USER = Permission::create([
            'name' => 'unlock-user',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$CREATE_PACKAGE = Permission::create([
            'name' => 'create-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_ALL_PACKAGE = Permission::create([
            'name' => 'read-all-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_A_PACKAGE = Permission::create([
            'name' => 'read-a-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UPDATE_PACKAGE = Permission::create([
            'name' => 'update-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$DELETE_PACKAGE = Permission::create([
            'name' => 'delete-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$REGISTER_PACKAGE = Permission::create([
            'name' => 'register-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UNREGISTER_PACKAGE = Permission::create([
            'name' => 'unregister-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$EXTEND_PACKAGE = Permission::create([
            'name' => 'extend-package',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$CREATE_CATEGORY_BOOK = Permission::create([
            'name' => 'create-category-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_ALL_CATEGORY_BOOK = Permission::create([
            'name' => 'read-all-category-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_A_CATEGORY_BOOK = Permission::create([
            'name' => 'read-a-category-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UPDATE_CATEGORY_BOOK = Permission::create([
            'name' => 'update-category-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$DELETE_CATEGORY_BOOK = Permission::create([
            'name' => 'delete-category-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$CREATE_BOOK = Permission::create([
            'name' => 'create-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_ALL_BOOK = Permission::create([
            'name' => 'read-all-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_A_BOOK = Permission::create([
            'name' => 'read-a-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UPDATE_BOOK = Permission::create([
            'name' => 'update-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$DELETE_BOOK = Permission::create([
            'name' => 'delete-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$BORROW_BOOK = Permission::create([
            'name' => 'borrow-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$RETURN_BOOK = Permission::create([
            'name' => 'return-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$APPROVE_BOOK = Permission::create([
            'name' => 'approve-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$REJECT_BOOK = Permission::create([
            'name' => 'reject-book',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$PAY_MONEY = Permission::create([
            'name' => 'pay-money',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$READ_STATISTIC = Permission::create([
            'name' => 'read-statistic',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$CREATE_STATISTIC = Permission::create([
            'name' => 'create-statistic',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$UPDATE_STATISTIC = Permission::create([
            'name' => 'update-statistic',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
        self::$DELETE_STATISTIC = Permission::create([
            'name' => 'delete-statistic',
            'guard_name' => GlobalConstant::$GUARD_API
        ]);
    }
}
