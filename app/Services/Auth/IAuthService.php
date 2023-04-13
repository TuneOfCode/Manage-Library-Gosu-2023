<?php

namespace App\Services\Auth;
use App\Http\Requests\V1\Auth\ChangePasswordRequest;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Requests\V1\Auth\UpdateMeRequest;
/**
 * Hợp đồng 
 */
interface IAuthSerice {
    /**
     * Dịch vụ đăng ký thành viên hiện tại
     */
    public function register(RegisterRequest $registerData);
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     */
    public function login(LoginRequest $loginData);
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
    public function changePassword(ChangePasswordRequest $changePassData);
    /**
     * Dịch vụ cập nhật thông tin của thành viên hiện tại
     */
    public function updateMe(UpdateMeRequest $updateMeData);
}