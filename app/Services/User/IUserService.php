<?php

namespace App\Services\User;

use Illuminate\Http\Request;

interface IUserService {
    /**
     * Dịch vụ lấy tất cả người dùng 
     */
    public static function getAllUsers(Request $request);
    /**
     * Dịch vụ lấy chi tiết người dùng
     */
    public static function getAUser(Request $request, mixed $id);
    /**
     * Dịch vụ xóa người dùng
     */
    public static function deleteUser(mixed $id);
    /**
     * Dịch vụ khoá tài khoản người dùng
     */
    public static function lockUser(mixed $id);
    /**
     * Dịch vụ mở khoá tài khoản người dùng
     */
    public static function unlockUser(mixed $id);
}
