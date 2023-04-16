<?php

namespace App\Services\Auth;

/**
 * Hợp đồng 
 */
interface IAuthService {
    /**
     * Dịch vụ đăng ký thành viên hiện tại
     */
    public function register(mixed $registerData);
    /**
     * Dịch vụ xác thực mail thành viên hiện tại
     */
    public function verifyEmail(mixed $verifyEmailData);
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     */
    public function login(mixed $loginData);
    /**
     * Dịch vụ hiển thị thông tin thành viên hiện tại
     */
    public function me();
    /**
     * Dịch vụ quên mật khẩu
     */
    public function forgotPassword(string $email);
    /**
     * Dịch vụ thay đổi mật khẩu của thành viên hiện tại
     */
    public function changePassword(mixed $changePassData);
    /**
     * Dịch vụ cập nhật thông tin của thành viên hiện tại
     */
    public function updateMe(mixed $updateMeData);
}
