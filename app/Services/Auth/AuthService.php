<?php

namespace App\Services\Auth;

use App\Constants\GlobalConstant;
use App\Http\Responses\BaseResponse;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements IAuthService {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
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
        $registerDTO = [
            "full_name" => $registerData["full_name"],
            "email" => $registerData["email"],
            'username' => $registerData["username"],
            'password' => Hash::make($registerData['password']),
        ];
        // gọi dịch vụ đăng ký thành viên mới
        $newUser = $this->userRepo->create($registerDTO);
        $data = [
            "id" => $newUser["id"],
            // 'tokenType' => 'Bearer',
            // "token" => $newUser->createToken(GlobalConstant::$AUTH_TOKEN)->accessToken,
        ];
        // khi đăng ký thành công thì thành viên phải xác thực email 
        // mới có thể đăng nhập
        return $data;
    }
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     * @param mixed $loginData
     * @return 
     */
    public function login(mixed $loginData) {
        // kiểm tra đăng nhập
        if (!Auth::attempt($loginData)) {
            throw new \Exception("Tên đăng nhập hoặc mật khẩu không chính xác", 400);
        }
        // kiểm tra thành viên đã xác thực email hay chưa
        $emailVerifiedAt = Auth::user()->attributesToArray()['email_verified_at'];
        if (empty($emailVerifiedAt)) {
            throw new \Exception("Chưa xác nhận email", 401);
        }
        // kiểm tra tài khoản có bị khoá hay không
        $accountStatus = Auth::user()->attributesToArray()['status'];
        if ($accountStatus == 0) {
            throw new \Exception("Tài khoản này đã bị khoá", 401);
        }
        $personalToken = Auth::user()->createToken(GlobalConstant::$AUTH_TOKEN);
        $datetimeExpiresAt = new DateTime(
            $personalToken->token->attributesToArray()['expires_at'],
            new DateTimeZone('UTC')
        );
        $expiresAt = $datetimeExpiresAt
            ->setTimezone(new DateTimeZone(GlobalConstant::$FORMAT_TIMEZONE))
            ->format(GlobalConstant::$FORMAT_DATETIME);

        $data = [
            'tokenType' => 'Bearer',
            "token" => $personalToken->accessToken,
            "expiresAt" => $expiresAt,
        ];
        return $data;
    }
    /**
     * Dịch vụ hiển thị thông tin thành viên hiện tại
     * @return
     */
    public function me() {
        return Auth::user();
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
