<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ChangePasswordRequest;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Requests\V1\Auth\UpdateMeRequest;
use App\Http\Requests\V1\Auth\UploadAvatarRequest;
use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm tạo
     */
    public function __construct() {
        $this->middleware(
            'auth:api',
            ['except' => [
                'login',
                'register',
                'forgotPassword',
                'verifyEmail'
            ]]
        );
        new AuthService(new UserRepository());
    }
    /**
     * Điều hướng về đăng ký thành viên mới
     */
    public function register(RegisterRequest $registerData) {
        Log::info("***** Đăng ký thành viên mới *****");
        try {
            // gọi dịch vụ đăng ký thành viên mới
            $data = AuthService::register($registerData);
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
            $data = AuthService::verifyEmail($verifyEmailData);
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
            $data = AuthService::login($loginData->toArray());
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
            // gọi dịch vụ xử lý hiển thị thông tin thành viên hiện tại
            $data = AuthService::me();
            return $this->success($request, $data, "Lấy thông tin thành viên hiện tại thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Lấy thông tin thành viên hiện tại thất bại");
        }
    }
    /**
     * Điều hường về quên mật khẩu
     */
    public function forgotPassword(ForgotPasswordRequest $request) {
        Log::info("***** Quên mật khẩu *****");
        try {
            // gọi dịch vụ xử lý quên mật khẩu
            $data = AuthService::forgotPassword($request->email);
            return $this->success($request, $data, "Tạo mật khẩu mới và gửi email quên mật khẩu thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Tạo mật mới hoặc gửi email quên mật khẩu thất bại!");
        }
    }
    /**
     * Điều hướng về thay đổi mật khẩu của thành viên hiện tại
     */
    public function changePassword(ChangePasswordRequest $request) {
        Log::info("***** Thay đổi mật khẩu *****");
        try {
            // gọi dịch vụ xử lý thay đổi mật khẩu
            $data = AuthService::changePassword($request->toArray());
            return $this->success($request, $data, "Thay đổi mật khẩu thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Thay đổi mật khẩu thất bại!");
        }
    }
    /**
     * Điều hướng về cập nhật thông tin của thành viên hiện tại
     */
    public function updateMe(UpdateMeRequest $request) {
        Log::info("***** Cập nhật thông tin thành viên hiện tại *****");
        try {
            // gọi dịch vụ xử lý cập nhật thông tin thành viên hiện tại
            $data = AuthService::updateMe($request);
            return $this->success($request, $data, "Cập nhật thông tin thành viên thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Cập nhật thông tin thành viên thất bại!");
        }
    }
    /**
     * Điều hướng về tải ảnh đại diện của thành viên hiện tại
     */
    public function uploadAvatar(UploadAvatarRequest $request) {
        Log::info("***** Tải ảnh đại diện của thành viên hiện tại *****");
        try {
            // gọi dịch vụ xử lý tải ảnh đại diện của thành viên hiện tại
            $data = AuthService::uploadAvatar($request);
            return $this->success($request, $data, "Cập nhật ảnh đại diện thành viên thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Cập nhật ảnh đại diện thành viên thất bại!");
        }
    }
    /**
     * Điều hướng về refresh token
     */
    public function refreshToken(Request $request) {
        Log::info("***** Làm mới token *****");
        try {
            // gọi dịch vụ xử lý làm mới token
            $data = AuthService::refreshToken($request);
            return $this->success($request, $data, "Nhận refresh token thành công!");
        } catch (\Throwable $th) {
            return $this->error($request, $th, "Nhận refresh token thất bại!");
        }
    }
}
