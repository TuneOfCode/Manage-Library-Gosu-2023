<?php

namespace App\Services\Auth;

use App\Constants\GlobalConstant;
use App\Constants\MessageConstant;
use App\Constants\RoleConstant;
use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use App\Jobs\ForgotPasswordQueueJob;
use App\Jobs\VerifyEmailQueueJob;
use App\Repositories\User\IUserRepository;
use DateTime;
use DateTimeZone;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthService implements IAuthService {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Thuộc tính kho dữ liệu của thành viên
     */
    private static IUserRepository $userRepo;
    /**
     * Thuộc tính đường dẫn xác thực với oauth
     */
    private static string  $linkOauthToken;
    /**
     * Hàm khởi tạo
     */
    public function __construct(IUserRepository $userRepo) {
        self::$userRepo = $userRepo;
    }
    /**
     * Dịch vụ tạo token cho thành viên
     * @param mixed $authUser
     * @return array
     */
    public static function generateToken(mixed $authUser) {
        // tạo token cho thành viên
        $personalToken = $authUser->createToken(GlobalConstant::$AUTH_TOKEN);
        $datetimeAccessExpiresAt = new DateTime(
            $personalToken->token->attributesToArray()['expires_at'],
            new DateTimeZone('UTC')
        );
        $accessTokenExpiresAt = $datetimeAccessExpiresAt
            ->setTimezone(new DateTimeZone(GlobalConstant::$FORMAT_TIMEZONE))
            ->format(GlobalConstant::$FORMAT_DATETIME);

        // tạo refreshToken thông qua JWT trong passport
        $privateKey = file_get_contents(storage_path(GlobalConstant::$OAUTH_PRIVATE_KEY));

        $payload = [
            'sub' => $authUser->id,
            'iat' => time(),
            'exp' =>  time() + GlobalConstant::$REFRESH_TOKEN_LIFE_TIME,
        ];
        $key = new Key($privateKey, GlobalConstant::$ALGORITHM);
        $refreshToken = JWT::encode(
            $payload,
            $key->getKeyMaterial(),
            GlobalConstant::$ALGORITHM
        );
        $refreshTokenExpiresAt =
            now()
            ->addDays(30)
            ->timezone(GlobalConstant::$FORMAT_TIMEZONE)
            ->format(GlobalConstant::$FORMAT_DATETIME);

        // lưu refresh token mới vào CSDL
        self::$userRepo->update(['refresh_token' => $refreshToken], $authUser->id);

        $result = [
            'tokenType' => GlobalConstant::$TYPE_TOKEN,
            "accessToken" => $personalToken->accessToken,
            "accessTokenExpiresAt" => $accessTokenExpiresAt,
            "refreshToken" => $refreshToken,
            "refreshTokenExpiresAt" => $refreshTokenExpiresAt,
        ];
        return $result;
    }
    /**
     * Dịch vụ đăng ký thành viên hiện tại
     * @param mixed $registerData
     * @return
     */
    public static function register(mixed $registerData) {
        // Xử lý logic nghiệp vụ đăng ký tài khoản
        $registerDTO = [
            "full_name" => $registerData["full_name"],
            "email" => $registerData["email"],
            'username' => $registerData["username"],
            'password' => Hash::make($registerData['password']),
        ];

        // gọi dịch vụ đăng ký thành viên mới
        $newUser = self::$userRepo->create($registerDTO);
        $result = [
            "id" => $newUser["id"]
        ];

        // thiết lập vai trò và quyền cho thành viên mới
        $roleMember = Role::where(['name' => RoleConstant::$MEMBER])->first();
        $newUser->assignRole($roleMember);

        // gửi mail xác thực khi đăng ký thành công 
        $id = $newUser['id'];
        $email = $registerDTO['email'];
        $sendOtpEmailData = [
            'id' => $id,
            'full_name' => $registerDTO['full_name'],
            'email' => $email
        ];
        self::sendOtpEmail($sendOtpEmailData);

        return $result;
    }
    /**
     * Dịch vụ gửi mã otp thông qua email của thành viên hiện tại
     */
    public static function sendOtpEmail(mixed $sendOtpEmailData) {
        $email = $sendOtpEmailData['email'];
        $user = self::$userRepo->findOne([
            'email' => $email,
        ])->toArray();

        // kiểm tra thành viên có tồn tại
        if (empty($user)) {
            throw new \Exception(
                MessageConstant::$MEMBER_NOT_EXIST,
                BaseHTTPResponse::$NOT_FOUND
            );
        }

        // kiểm tra thành viên đã xác thực email chưa
        if ($user['email_verified_at'] !== null) {
            throw new \Exception(
                MessageConstant::$MEMBER_VERIFIED_EMAIL,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // kiểm tra thành viên đã gửi mã otp trước đó và mã còn hạn sử dụng
        if ($user['otp_email_expired_at'] !== null && $user['otp_email_expired_at'] > now()) {
            throw new \Exception(
                MessageConstant::$OTP_IS_STILL_USABLE,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // tạo và lưu mã otp vào CSDL
        $otpEmailCode = substr(time(), -3) . Str::random(3);
        $otpEmailExpiresAt = now()->addMinutes(1);
        self::$userRepo->update([
            'otp_email_code' => $otpEmailCode,
            'otp_email_expired_at' => $otpEmailExpiresAt,
        ], $user['id']);
        $sendOtpEmailData['id'] = $user['id'];
        $sendOtpEmailData['full_name'] = $user['full_name'];
        $sendOtpEmailData['otp_email_code'] = $otpEmailCode;
        $sendOtpEmailData['otp_email_expired_at'] = $otpEmailExpiresAt->toString();

        // gửi mail xác thực
        dispatch(new VerifyEmailQueueJob(
            $sendOtpEmailData['email'],
            $sendOtpEmailData
        ));
    }
    /**
     * Dịch vụ xác thực mail thành viên hiện tại
     */
    public static function verifyEmail(mixed $verifyEmailData) {
        $email = $verifyEmailData['email'];
        $user = self::$userRepo->findOne([
            'email' => $email,
        ]);

        // kiểm tra thành viên có tồn tại
        if (empty($user)) {
            throw new \Exception(
                MessageConstant::$MEMBER_NOT_EXIST,
                BaseHTTPResponse::$NOT_FOUND
            );
        }
        $id = $user['id'];

        // kiểm tra thành viên đã xác thực email trước đó chưa
        if (!empty($user->email_verified_at)) {
            throw new \Exception(
                MessageConstant::$MEMBER_VERIFIED_EMAIL,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // kiểm tra mã otp có hợp lệ
        if (
            empty($user->otp_email_code)
            || $user->otp_email_code != $verifyEmailData['otp_email_code']
            || empty($user->otp_email_expired_at)
            || $user->otp_email_expired_at < now()
        ) {
            throw new \Exception(
                MessageConstant::$INVALID_OTP_CODE,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        $dataUpdate = [
            'email_verified_at' => date(GlobalConstant::$FORMAT_DATETIME_DB, time()),
        ];

        // cập nhật lại thông tin thành viên
        self::$userRepo->update($dataUpdate, $id);

        $meta = self::generateToken($user);
        $user = self::$userRepo->findById($id);
        $result = [
            'data' => $user,
            'meta' => $meta
        ];
        return $result;
    }
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     * @param mixed $loginData
     * @return 
     */
    public static function login(mixed $loginData) {
        // kiểm tra người dùng trước đó đã đăng nhập hay chưa
        if (Auth::check()) {
            throw new \Exception(
                MessageConstant::$ALREADY_LOGIN,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        $loginData['email'] = $loginData['username'];
        // kiểm tra đăng nhập
        if (!Auth::attempt([
            'email' => $loginData['email'],
            'password' => $loginData['password']
        ]) && !Auth::attempt([
            'username' => $loginData['username'],
            'password' => $loginData['password']
        ])) {
            throw new \Exception(
                MessageConstant::$WRONG_LOGIN,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        $authUser = Auth::user();

        // kiểm tra thành viên đã xác thực email hay chưa
        $emailVerifiedAt = $authUser['email_verified_at'];
        if (empty($emailVerifiedAt)) {
            // gửi email khi người dùng đăng nhập vào mà chưa xác thực email
            $sendOtpEmailData = [
                'id' => $authUser['id'],
                'full_name' => $authUser['full_name'],
                'email' => $authUser['email']
            ];
            self::sendOtpEmail($sendOtpEmailData);
            throw new \Exception(
                MessageConstant::$EMAIL_NOT_VERIFIED,
                BaseHTTPResponse::$UNAUTHORIZED
            );
        }

        // kiểm tra tài khoản có bị khoá hay không
        $accountStatus = $authUser['status'];
        if ($accountStatus == 0) {
            throw new \Exception(
                MessageConstant::$ACCOUNT_LOCKED,
                BaseHTTPResponse::$UNAUTHORIZED
            );
        }

        // tạo token cho thành viên
        $meta = self::generateToken($authUser);
        $result = [
            'data' => $authUser,
            'meta' => $meta
        ];
        return $result;
    }
    /**
     * Dịch vụ hiển thị thông tin thành viên hiện tại
     * @return
     */
    public static function me() {
        return Auth::user();
    }
    /**
     * Dịch vụ quên mật khẩu
     * @param string $email
     * @return
     */
    public static function forgotPassword(string $email) {
        // trường hợp người dùng đã đăng nhập
        if (Auth::check()) {
            throw new \Exception(
                MessageConstant::$YOU_LOGGED,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // kiểm tra email có tồn tại trong CSDL
        $user = self::$userRepo->findOne(['email' => $email]);
        if (empty($user)) {
            throw new \Exception(
                MessageConstant::$EMAIL_NOT_EXIST,
                BaseHTTPResponse::$NOT_FOUND
            );
        }

        // Tạo ngẫu nhiên mật khẩu và mã hoá nó
        $newPassword = Str::random(8);
        $hashNewPassword = Hash::make($newPassword);

        // Lưu mật khẩu mới vào CSDL
        self::$userRepo->update(
            ['password' => $hashNewPassword],
            $user->id
        );
        $forgotPasswordData = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'new_password' => $newPassword
        ];

        // Gửi email cho người dùng
        dispatch(new ForgotPasswordQueueJob($email, $forgotPasswordData));
        $result = [
            'id' => $user->id,
            'newPassword' => $newPassword
        ];
        return $result;
    }
    /**
     * Dịch vụ thay đổi mật khẩu của thành viên hiện tại
     * @param mixed $changePassData
     * @return
     */
    public static function changePassword(mixed $changePassData) {
        $user = Auth::user();
        $id = $user->id;
        $oldPassword = $changePassData['oldPassword'];
        $hashOldPassword = $user->password;

        // kiểm tra mật khẩu cũ có khớp với mật khẩu trong CSDL
        if (!Hash::check($oldPassword, $hashOldPassword)) {
            throw new \Exception(
                MessageConstant::$WRONG_OLD_PASSWORD,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // Mã hoá mật khẩu mới
        $newHashPassword = Hash::make($changePassData['newPassword']);

        // Cập nhật mật khẩu mới vào CSDL
        self::$userRepo->update(['password' => $newHashPassword], $id);

        // $result = [
        //     'id' => $id,
        //     'newPassword' => $changePassData['newPassword'],
        // ];
        // return $result;
        return null;
    }
    /**
     * Dịch vụ cập nhật thông tin của thành viên hiện tại
     * @param mixed $updateMeData
     * @return
     */
    public static function updateMe(mixed $updateMeData) {
        $user = Auth::user();
        $id = $user->id;

        // // kiểm tra email có tồn tại trong CSDL
        // if (!empty($updateMeData['email']) && isset($updateMeData['email'])) {
        //     $user = self::$userRepo->findOne(['email' => $updateMeData['email']]);
        //     if (!empty($user->email) && $user->id != $id) {
        //         throw new \Exception(
        //             MessageConstant::$EMAIL_EXIST,
        //             BaseHTTPResponse::$BAD_REQUEST
        //         );
        //     }
        // }

        // cập nhật lại thông tin thành viên
        $updateMeRequest = [
            'full_name' => empty($updateMeData['fullName']) || !isset($updateMeData['fullName'])
                ? $user['full_name']
                : $updateMeData['fullName'],
            // 'email' => empty($updateMeData['email']) || !isset($updateMeData['email'])
            //     ? $user['email']
            //     : $updateMeData['email'],
            'phone' => empty($updateMeData['phone']) || !isset($updateMeData['phone'])
                ? $user['phone']
                : $updateMeData['phone'],
            'address' => empty($updateMeData['address']) || !isset($updateMeData['address'])
                ? $user['address']
                : $updateMeData['address'],
        ];

        self::$userRepo->update($updateMeRequest, $id);
        $user = self::$userRepo->findOne(['id' => $id]);

        return $user;
    }
    /**
     * Dịch vụ tải ảnh đại diện của thành viên hiện tại
     * @param mixed $request
     * @return
     */
    public static function uploadAvatar(mixed $request) {
        $avatar = $request->file('avatar');
        // Đặt lại tên file
        $currentDatetime = now()->format('YmdHis');
        $fileName = $avatar->getClientOriginalName();
        $avatarName = "$currentDatetime-$fileName";

        // Lưu file vào thư mục
        $path = $avatar->storeAs('public/avatars', $avatarName);

        // lấy đường liên kết
        $linkAvatar = Storage::url($path);

        // Cập nhật thông tin ảnh đại diện vào CSDL
        $user = Auth::user();
        $id = $user->id;
        self::$userRepo->update(['avatar' => $linkAvatar], $id);

        $result = [
            'id' => $id,
            'avatar' => $avatarName,
            'linkAvatar' => $linkAvatar,
        ];
        return $result;
    }
    /**
     * Dịch vụ làm mới token
     * @param mixed $request
     * @return
     */
    public static function refreshToken(mixed $request) {
        $refreshToken = $request['refreshToken'];
        // giải mã refresh token
        $publicKey = file_get_contents(storage_path(GlobalConstant::$OAUTH_PUBLIC_KEY));
        $key = new Key($publicKey, GlobalConstant::$ALGORITHM);
        $decoded = (array) JWT::decode($refreshToken, $key);
        $userId = $decoded['sub'];
        $user = self::$userRepo->findOne(['id' => $userId]);

        // kiểm tra người dùng có tồn tại trong CSDL
        if (empty($user->id)) {
            throw new \Exception(
                MessageConstant::$MEMBER_NOT_EXIST,
                BaseHTTPResponse::$NOT_FOUND
            );
        }

        // kiểm tra refresh token có khớp với refresh token trong CSDL
        if ($refreshToken != $user->refresh_token) {
            throw new \Exception(
                MessageConstant::$INVALID_TOKEN,
                BaseHTTPResponse::$UNAUTHORIZED
            );
        }

        // tạo token cho thành viên
        $meta = self::generateToken($user);
        $result = [
            'data' => $user,
            'meta' => $meta,
        ];
        return $result;
    }
}
