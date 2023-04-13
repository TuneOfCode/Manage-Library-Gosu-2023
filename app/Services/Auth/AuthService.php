<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepository;

class AuthService implements IAuthService {
    /**
     * Thuộc tính kho dữ liệu của thành viên
     */
    private UserRepository $userRepo;
    /**
     * Hàm khởi tạo
     */
    public function __construct() {
        $this->userRepo = new UserRepository();
    }
    /**
     * Dịch vụ đăng ký thành viên hiện tại
     * @param mixed $registerData
     * @return
     */
    public function register(mixed $registerData) {
        // Xử lý logic nghiệp vụ đăng ký tài khoản
        return $this->userRepo->create($registerData);
    }
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     * @param mixed $loginData
     * @return 
     */
    public function login(mixed $loginData) {
        return [];
    }
    /**
     * Dịch vụ hiển thị thông tin thành viên hiện tại
     * @return
     */
    public function me() {
        return [];
    }
    /**
     * Dịch vụ quên mật khẩu
     * @param string $email
     * @return
     */
    public function forgotPassword(string $email) {
        return [];
    }
    /**
     * Dịch vụ thay đổi mật khẩu của thành viên hiện tại
     * @param mixed $changePassData
     * @return
     */
    public function changePassword(mixed $changePassData) {
        return [];
    }
    /**
     * Dịch vụ cập nhật thông tin của thành viên hiện tại
     * @param mixed $updateMeData
     * @return
     */
    public function updateMe(mixed $updateMeData) {
        return [];
    }
}
