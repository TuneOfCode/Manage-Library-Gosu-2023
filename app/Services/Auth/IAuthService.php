<?php

namespace App\Services\Auth;

/**
 * Hợp đồng 
 */
interface IAuthService {
    /**
     * Dịch vụ tạo token 
     */
    public static function generateToken(mixed $authUser);
    /**
     * Dịch vụ đăng ký thành viên hiện tại
     */
    public static function register(mixed $registerData);
    /**
     * Dịch vụ xác thực mail thành viên hiện tại
     */
    public static function verifyEmail(mixed $verifyEmailData);
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     */
    public static function login(mixed $loginData);
    /**
     * Dịch vụ hiển thị thông tin thành viên hiện tại
     */
    public static function me();
    /**
     * Dịch vụ quên mật khẩu
     */
    public static function forgotPassword(string $email);
    /**
     * Dịch vụ thay đổi mật khẩu của thành viên hiện tại
     */
    public static function changePassword(mixed $changePassData);
    /**
     * Dịch vụ cập nhật thông tin của thành viên hiện tại
     */
    public static function updateMe(mixed $updateMeData);
    /**
     * Dịch vụ tải ảnh đại diện của thành viên hiện tại
     */
    public static function uploadAvatar(mixed $request);
    /**
     * Dịch vụ làm mới token
     */
    public static function refreshToken(mixed $request);
}
