<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ChangePasswordRequest;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Requests\V1\Auth\ResendOtpEmailRequest;
use App\Http\Requests\V1\Auth\UpdateMeRequest;
use App\Http\Requests\V1\Auth\UploadAvatarRequest;
use App\Http\Requests\V1\Auth\VerifyEmailRequest;
use App\Http\Resources\V1\Auth\AuthResource;
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
                'resendOtpEmail',
                'verifyEmail',
                'refreshToken'
            ]]
        );
        new AuthService(new UserRepository());
    }
    /**
     * Điều hướng về đăng ký thành viên mới
     */
    public function register(RegisterRequest $request) {
        Log::info("***** Đăng ký thành viên mới *****");
        try {
            // gọi dịch vụ đăng ký thành viên mới
            $data = AuthService::register($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$REGISTER_SUCCESS,
                BaseHTTPResponse::$CREATED
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$REGISTER_FAILED,
            );
        }
    }
    /**
     * Điều hướng gửi lại mã otp thông qua email
     */
    public function resendOtpEmail(ResendOtpEmailRequest $request) {
        Log::info("***** Gửi lại mã otp thông qua email *****");
        try {
            // gọi dịch vụ gửi lại mã otp thông qua email
            AuthService::sendOtpEmail($request->all());
            return $this->success(
                $request,
                null,
                MessageConstant::$RESEND_OTP_EMAIL_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$RESEND_OTP_EMAIL_FAILED
            );
        }
    }
    /**
     * Điều hướng xác thực email
     */
    public function verifyEmail(VerifyEmailRequest $request) {
        Log::info("***** Xác thực email *****");
        try {
            // gọi dịch vụ xác thực email
            $data = AuthService::verifyEmail($request->all());
            $data['data'] = new AuthResource($data['data']);
            return $this->success(
                $request,
                $data,
                MessageConstant::$VERIFY_EMAIL_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$VERIFY_EMAIL_FAILED
            );
        }
    }
    /**
     * Điều hướng về đăng nhập thành viên
     */
    public function login(LoginRequest $request) {
        Log::info("***** Đăng nhập thành viên *****");
        try {
            // gọi dịch vụ xử lý đăng nhập
            $data = AuthService::login($request->toArray());
            $data['data'] = new AuthResource($data['data']);
            return $this->success(
                $request,
                $data,
                MessageConstant::$LOGIN_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$LOGIN_FAILED
            );
        }
    }
    /**
     * Điều hướng về bản thân thành viên hiện tại
     */
    public function me(Request $request) {
        Log::info("***** Thông tin thành viên hiện tại *****");
        try {
            // gọi dịch vụ xử lý hiển thị thông tin thành viên hiện tại
            $data = new AuthResource(AuthService::me());
            return $this->success(
                $request,
                $data,
                MessageConstant::$ME_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$ME_FAILED
            );
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
            return $this->success(
                $request,
                $data,
                MessageConstant::$FORGOT_PASSWORD_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$FORGOT_PASSWORD_FAILED
            );
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
            return $this->success(
                $request,
                $data,
                MessageConstant::$CHANGE_PASSWORD_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CHANGE_PASSWORD_FAILED
            );
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
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPDATE_ME_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPDATE_ME_FAILED
            );
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
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPLOAD_AVATAR_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPLOAD_AVATAR_FAILED
            );
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
            $data['data'] = new AuthResource($data['data']);
            return $this->success(
                $request,
                $data,
                MessageConstant::$REFRESH_TOKEN_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$REFRESH_TOKEN_FAILED
            );
        }
    }
}
