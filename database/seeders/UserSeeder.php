<?php

namespace Database\Seeders;

use App\Constants\GlobalConstant;
use App\Constants\PermissionConstant;
use App\Constants\RoleConstant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        #region tạo tất cả quyền trong hệ thống
        $CREATE_USER = Permission::create([
            "name" => PermissionConstant::$CREATE_USER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_ALL_USER = Permission::create([
            "name" => PermissionConstant::$READ_ALL_USER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_A_USER = Permission::create([
            "name" => PermissionConstant::$READ_A_USER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $UPDATE_USER = Permission::create([
            "name" => PermissionConstant::$UPDATE_USER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $DELETE_USER = Permission::create([
            "name" => PermissionConstant::$DELETE_USER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $LOCK_USER = Permission::create([
            "name" => PermissionConstant::$LOCK_USER,
            "guard_name" => GlobalConstant::$GUARD_API,
        ]);
        $UNLOCK_USER = Permission::create([
            "name" => PermissionConstant::$UNLOCK_USER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $CREATE_PACKAGE = Permission::create([
            "name" => PermissionConstant::$CREATE_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_ALL_PACKAGE = Permission::create([
            "name" => PermissionConstant::$READ_ALL_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_A_PACKAGE = Permission::create([
            "name" => PermissionConstant::$READ_A_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $UPDATE_PACKAGE = Permission::create([
            "name" => PermissionConstant::$UPDATE_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $DELETE_PACKAGE = Permission::create([
            "name" => PermissionConstant::$DELETE_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $REGISTER_PACKAGE = Permission::create([
            "name" => PermissionConstant::$REGISTER_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $UNREGISTER_PACKAGE = Permission::create([
            "name" => PermissionConstant::$UNREGISTER_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $EXTEND_PACKAGE = Permission::create([
            "name" => PermissionConstant::$EXTEND_PACKAGE,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $CREATE_CATEGORY_BOOK = Permission::create([
            "name" => PermissionConstant::$CREATE_CATEGORY_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_ALL_CATEGORY_BOOK = Permission::create([
            "name" => PermissionConstant::$READ_ALL_CATEGORY_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_A_CATEGORY_BOOK = Permission::create([
            "name" => PermissionConstant::$READ_A_CATEGORY_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $UPDATE_CATEGORY_BOOK = Permission::create([
            "name" => PermissionConstant::$UPDATE_CATEGORY_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $DELETE_CATEGORY_BOOK = Permission::create([
            "name" => PermissionConstant::$DELETE_CATEGORY_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $CREATE_BOOK = Permission::create([
            "name" => PermissionConstant::$CREATE_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_ALL_BOOK = Permission::create([
            "name" => PermissionConstant::$READ_ALL_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $READ_A_BOOK = Permission::create([
            "name" => PermissionConstant::$READ_A_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $UPDATE_BOOK = Permission::create([
            "name" => PermissionConstant::$UPDATE_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $DELETE_BOOK = Permission::create([
            "name" => PermissionConstant::$DELETE_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $BORROW_BOOK = Permission::create([
            "name" => PermissionConstant::$BORROW_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $CANCEL_BOOK = Permission::create([
            "name" => PermissionConstant::$CANCEL_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $CONFIRM_RETURNED_BOOK = Permission::create([
            "name" => PermissionConstant::$CONFIRM_RETURNED_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $APPROVE_BOOK = Permission::create([
            "name" => PermissionConstant::$APPROVE_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $REJECT_BOOK = Permission::create([
            "name" => PermissionConstant::$REJECT_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $PAY_MONEY = Permission::create([
            "name" => PermissionConstant::$PAY_MONEY,
            "guard_name" => GlobalConstant::$GUARD_API,
        ]);
        $CONFIRM_RECEIVED_BOOK = Permission::create([
            "name" => PermissionConstant::$CONFIRM_RECEIVED_BOOK,
            "guard_name" => GlobalConstant::$GUARD_API,
        ]);
        $READ_STATISTIC = Permission::create([
            "name" => PermissionConstant::$READ_STATISTIC,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $CREATE_STATISTIC = Permission::create([
            "name" => PermissionConstant::$CREATE_STATISTIC,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $UPDATE_STATISTIC = Permission::create([
            "name" => PermissionConstant::$UPDATE_STATISTIC,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $DELETE_STATISTIC = Permission::create([
            "name" => PermissionConstant::$DELETE_STATISTIC,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        #endregion

        #region tạo tất cả vai trò trong hệ thống
        $roleAdmin = Role::create([
            "name" => RoleConstant::$ADMIN,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        $roleMember = Role::create([
            "name" => RoleConstant::$MEMBER,
            "guard_name" => GlobalConstant::$GUARD_API
        ]);
        #endregion

        #region gán quyền cho vai trò quản trị viên
        $adminPermissions = [
            // users
            $CREATE_USER,
            $READ_ALL_USER,
            $READ_A_USER,
            $UPDATE_USER,
            $DELETE_USER,
            $LOCK_USER,
            $UNLOCK_USER,
            // packages
            $CREATE_PACKAGE,
            $READ_ALL_PACKAGE,
            $READ_A_PACKAGE,
            $UPDATE_PACKAGE,
            $DELETE_PACKAGE,
            // categories
            $CREATE_CATEGORY_BOOK,
            $READ_ALL_CATEGORY_BOOK,
            $READ_A_CATEGORY_BOOK,
            $UPDATE_CATEGORY_BOOK,
            $DELETE_CATEGORY_BOOK,
            // books
            $CREATE_BOOK,
            $READ_ALL_BOOK,
            $READ_A_BOOK,
            $UPDATE_BOOK,
            $DELETE_BOOK,
            $APPROVE_BOOK,
            $REJECT_BOOK,
            $CONFIRM_RECEIVED_BOOK,
            $CONFIRM_RETURNED_BOOK,
            // statistics
            $READ_STATISTIC,
            $CREATE_STATISTIC,
            $UPDATE_STATISTIC,
            $DELETE_STATISTIC,
        ];
        // tạo tài khoản admin
        $admin = User::create([
            "full_name" => "admin",
            "email" => "thuctapgosu@gmail.com",
            "username" => "admin",
            "password" => Hash::make("thuctapgosu2023"),
            "avatar" => fake()->imageUrl(),
            "score" => "100",
            "balance" => "999999.99",
            "otp_email_code" => "abc123",
            "otp_email_expired_at" => now()->addMinute(),
            "email_verified_at" => now(),
            "package_id" => 4
        ]);
        $admin->assignRole($roleAdmin)->givePermissionTo($adminPermissions);
        #endregion

        #region gán quyền cho vai trò thành viên
        $memberPermissions = [
            // packages
            $READ_ALL_PACKAGE,
            $READ_A_PACKAGE,
            $REGISTER_PACKAGE,
            $UNREGISTER_PACKAGE,
            $EXTEND_PACKAGE,
            // categories
            $READ_ALL_CATEGORY_BOOK,
            $READ_A_CATEGORY_BOOK,
            // books
            $READ_ALL_BOOK,
            $READ_A_BOOK,
            $BORROW_BOOK,
            $CANCEL_BOOK,
            $PAY_MONEY
        ];

        // tạo tài khoản member
        $members = User::factory()
            ->count(14)
            ->hasPackage(random_int(1, 4))
            ->create();
        $members->each(function ($member) use ($roleMember) {
            if (!$member->hasRole(RoleConstant::$ADMIN)) {
                $member->assignRole($roleMember)
                    ->givePermissionTo($member->getAllPermissions());
            }
        });
        #endregion

        #region phân quyền cho từng vai trò
        $roleAdmin->givePermissionTo($adminPermissions);
        $roleMember->givePermissionTo($memberPermissions);
        #endregion
    }
}
