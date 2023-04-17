<?php

namespace App\Services\Auth;

use App\Constants\GlobalConstant;
use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use App\Mail\ForgotPassword;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use DateTime;
use DateTimeZone;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Token;
use Illuminate\Support\Str;

class AuthService implements IAuthService {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Thuộc tính kho dữ liệu của thành viên
     */
    private IUserRepository $userRepo;
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
        // gửi mail cho thành viên khi đăng ký thành công
        $id = $newUser['id'];
        $token = $newUser->createToken(GlobalConstant::$AUTH_TOKEN)->accessToken;
        $email = $registerDTO['email'];
        $registerDTO['id'] = $id;
        $registerDTO['token'] = $token;
        Mail::to($email)->send(new VerifyEmail($registerDTO));

        return $data;
    }
    /**
     * Dịch vụ xác thực mail thành viên hiện tại
     */
    public function verifyEmail(mixed $verifyEmailData) {
        $id = $verifyEmailData['id'];
        $token = $verifyEmailData['token'];
        $user = $this->userRepo->findById($id);
        // kiểm tra thành viên có tồn tại
        if (empty($user)) {
            throw new \Exception("Thành viên không tồn tại", BaseHTTPResponse::$NOT_FOUND);
        }
        // kiểm tra thành viên đã xác thực email trước đó chưa
        if (!empty($user->email_verified_at)) {
            throw new \Exception("Thành viên đã xác thực email trước đó", BaseHTTPResponse::$BAD_REQUEST);
        }
        // Lấy khóa công khai
        $publicKey = file_get_contents(storage_path(GlobalConstant::$OAUTH_PUBLIC_KEY));
        $decode = JWT::decode($token, new Key($publicKey, GlobalConstant::$ALGORITHM));

        $decodeID = $decode->jti;
        $dataDecode = Token::where('id', $decodeID)->first()->attributesToArray();

        // kiểm tra token có hợp lệ
        if (
            !is_array($dataDecode)
            || (is_array($dataDecode) && count($dataDecode) === 0)
            || $dataDecode['user_id'] != $id
        ) {
            throw new \Exception("Token không chính xác", BaseHTTPResponse::$UNAUTHORIZED);
        }

        $dataUpdate = [
            'email_verified_at' => date(GlobalConstant::$FORMAT_DATETIME_DB, time()),
            'access_token' => $token,
        ];

        // cập nhật lại thông tin thành viên
        $this->userRepo->update($dataUpdate, $id);

        $result = [
            'id' => $user->id,
            'tokenType' => 'Bearer',
            'token' => $token,
            'emailVerifiedAt' => $user->email_verified_at,
        ];

        return $result;
    }
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     * @param mixed $loginData
     * @return 
     */
    public function login(mixed $loginData) {
        // kiểm tra đăng nhập
        if (!Auth::attempt($loginData)) {
            throw new \Exception("Tên đăng nhập hoặc mật khẩu không chính xác", BaseHTTPResponse::$BAD_REQUEST);
        }
        $authUser = Auth::user();
        $currentUser = $authUser->attributesToArray();
        // kiểm tra thành viên đã xác thực email hay chưa
        $emailVerifiedAt = $currentUser['email_verified_at'];
        if (empty($emailVerifiedAt)) {
            // gửi email khi người dùng đăng nhập vào mà chưa xác thực email
            $id = $currentUser['id'];
            $token = $authUser->createToken(GlobalConstant::$AUTH_TOKEN)->accessToken;
            $email = $currentUser['email'];
            $currentUser['id'] = $id;
            $currentUser['token'] = $token;
            Mail::to($email)->send(new VerifyEmail($currentUser));
            throw new \Exception("Chưa xác nhận email. Hệ thống đã gửi mail cho bạn. Vui lòng vào email để xác thực!", BaseHTTPResponse::$UNAUTHORIZED);
        }
        // kiểm tra tài khoản có bị khoá hay không
        $accountStatus = $authUser->attributesToArray()['status'];
        if ($accountStatus == 0) {
            throw new \Exception("Tài khoản này đã bị khoá", BaseHTTPResponse::$UNAUTHORIZED);
        }
        $personalToken = $authUser->createToken(GlobalConstant::$AUTH_TOKEN);
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
        // trường hợp người dùng đã đăng nhập
        if (Auth::check()) {
            throw new \Exception("Bạn đã đăng nhập", BaseHTTPResponse::$BAD_REQUEST);
        }
        // kiểm tra email có tồn tại trong CSDL
        $user = $this->userRepo->findOne(['email' => $email]);
        if (empty($user->email)) {
            throw new \Exception("Email không tồn tại", BaseHTTPResponse::$NOT_FOUND);
        }
        // Tạo ngẫu nhiên mật khẩu và mã hoá nó
        $password = Str::random(8);
        $hashPassword = Hash::make($password);
        // Lưu mật khẩu mới vào CSDL
        $this->userRepo->update(['password' => $hashPassword], $user->id);
        $user['password'] = $password;
        // Gửi email cho người dùng
        Mail::to($email)->send(new ForgotPassword($user));
        $result = [
            'id' => $user->id,
            'newPassword' => $password,
        ];

        return $result;
    }
    /**
     * Dịch vụ thay đổi mật khẩu của thành viên hiện tại
     * @param mixed $changePassData
     * @return
     */
    public function changePassword(mixed $changePassData) {
        $user = Auth::user();
        $id = $user->id;
        $hashOldPassword = $user->password;
        $oldPassword = $changePassData['oldPassword'];
        // kiểm tra mật khẩu cũ có khớp với mật khẩu trong CSDL
        if (!Hash::check($oldPassword, $hashOldPassword)) {
            throw new \Exception("Mật khẩu cũ không đúng", BaseHTTPResponse::$BAD_REQUEST);
        }
        // Mã hoá mật khẩu mới
        $newHashPassword = Hash::make($changePassData['newPassword']);
        // Cập nhật mật khẩu mới vào CSDL
        $this->userRepo->update(['password' => $newHashPassword], $id);

        $result = [
            'id' => $id,
            'newPassword' => $changePassData['newPassword'],
        ];

        return $result;
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
