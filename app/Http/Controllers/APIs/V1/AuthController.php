<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Thuộc tính dịch vụ xử lý xác thực thành viên
     */
    private AuthService $authService;
    /**
     * Hàm tạo
     */
    public function __construct() {
        $this->authService = new AuthService();
    }
    /**
     * Điều hướng về đăng ký thành viên mới
     */
    public function register(RegisterRequest $registerData) {
        Log::info("***** Đăng ký thành viên mới *****");
        try {
            // gọi dịch vụ đăng ký thành viên mới
            $data = $this->authService->register($registerData);
            return $this->success($registerData, $data, "Đăng ký thành công! Vui lòng vào email và xác nhận.", BaseHTTPResponse::$CREATED);
        } catch (\Throwable $th) {
            return $this->error($registerData, $th, "Đăng ký thất bại!");
        }
    }
    /**
     * Điều hướng xác thực email
     */
    public function verifyEmail(Request $request) {
        Log::info("***** Xác thực email *****");
        $verifyEmailData = [
            "id" => $request->id,
            "tokenType" => "Bearer",
            "token" => $request->token,
        ];

        try {
            // gọi dịch vụ xác thực email
            $data = $this->authService->verifyEmail($verifyEmailData);
            return $this->success($request, $data, "Xác thực email thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Xác thực thất bại!");
        }
    }
    /**
     * Điều hướng về đăng nhập thành viên
     */
    public function login(LoginRequest $loginData) {
        Log::info("***** Đăng nhập thành viên *****");
        try {
            // gọi dịch vụ xử lý đăng nhập
            $data = $this->authService->login($loginData->toArray());
            return $this->success($loginData, $data, "Đăng nhập thành công!");
        } catch (\Throwable $th) {
            return $this->error($loginData, $th, "Đăng nhập thất bại!");
        }
    }
    /**
     * Điều hướng về bản thân thành viên hiện tại
     */
    public function me(Request $request) {
        Log::info("***** Thông tin thành viên hiện tại *****");
        try {
            // gọi dịch vụ xử lý đăng nhập
            $data = $this->authService->me();
            return $this->success($request, $data, "Lấy thông tin thành viên hiện tại thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Lấy thông tin thành viên hiện tại thất bại");
        }
    }
}
