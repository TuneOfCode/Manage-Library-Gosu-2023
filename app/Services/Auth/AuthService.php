<?php

namespace App\Services\Auth;

use App\Constants\GlobalConstant;
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
        $refreshToken = JWT::encode($payload, $key->getKeyMaterial(), GlobalConstant::$ALGORITHM);
        $refreshTokenExpiresAt =
            now()->addDays(30)->timezone(GlobalConstant::$FORMAT_TIMEZONE)->format(GlobalConstant::$FORMAT_DATETIME);

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

        // gửi mail xác thực khi đăng ký thành công 
        $id = $newUser['id'];
        $token = $newUser->createToken(GlobalConstant::$AUTH_TOKEN)->accessToken;
        $email = $registerDTO['email'];
        $registerDTO['id'] = $id;
        $registerDTO['token'] = $token;
        dispatch(new VerifyEmailQueueJob($email, $registerDTO));

        return $result;
    }
    /**
     * Dịch vụ xác thực mail thành viên hiện tại
     */
    public static function verifyEmail(mixed $verifyEmailData) {
        $id = $verifyEmailData['id'];
        $token = $verifyEmailData['token'];
        $user = self::$userRepo->findById($id);

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
        ];

        // cập nhật lại thông tin thành viên
        self::$userRepo->update($dataUpdate, $id);

        $result = [
            'id' => $user->id,
            'tokenType' => GlobalConstant::$TYPE_TOKEN,
            'token' => $token,
            'emailVerifiedAt' => date(GlobalConstant::$FORMAT_DATETIME_DB, time()),
        ];
        return $result;
    }
    /**
     * Dịch vụ đăng nhập thành viên hiện tại
     * @param mixed $loginData
     * @return 
     */
    public static function login(mixed $loginData) {
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
            dispatch(new VerifyEmailQueueJob($email, $currentUser));
            throw new \Exception("Chưa xác nhận email. Hệ thống đã gửi mail cho bạn. Vui lòng vào email để xác thực!", BaseHTTPResponse::$UNAUTHORIZED);
        }

        // kiểm tra tài khoản có bị khoá hay không
        $accountStatus = $authUser->attributesToArray()['status'];
        if ($accountStatus == 0) {
            throw new \Exception("Tài khoản này đã bị khoá", BaseHTTPResponse::$UNAUTHORIZED);
        }

        // tạo token cho thành viên
        $result = self::generateToken($authUser);
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
            throw new \Exception("Bạn đã đăng nhập", BaseHTTPResponse::$BAD_REQUEST);
        }

        // kiểm tra email có tồn tại trong CSDL
        $user = self::$userRepo->findOne(['email' => $email]);
        if (empty($user->email)) {
            throw new \Exception("Email không tồn tại", BaseHTTPResponse::$NOT_FOUND);
        }

        // Tạo ngẫu nhiên mật khẩu và mã hoá nó
        $password = Str::random(8);
        $hashPassword = Hash::make($password);

        // Lưu mật khẩu mới vào CSDL
        self::$userRepo->update(['password' => $hashPassword], $user->id);
        $user['password'] = $password;

        // Gửi email cho người dùng
        dispatch(new ForgotPasswordQueueJob($email, $user));
        $result = [
            'id' => $user->id,
            'newPassword' => $password
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
            throw new \Exception("Mật khẩu cũ không chính xác", BaseHTTPResponse::$BAD_REQUEST);
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

        // kiểm tra email có tồn tại trong CSDL
        if (!empty($updateMeData['email']) && isset($updateMeData['email'])) {
            $user = self::$userRepo->findOne(['email' => $updateMeData['email']]);
            if (!empty($user->email) && $user->id != $id) {
                throw new \Exception("Email đã tồn tại", BaseHTTPResponse::$BAD_REQUEST);
            }
        }

        // cập nhật lại thông tin thành viên
        $updateMeRequest = [
            'full_name' => empty($updateMeData['fullName']) || !isset($updateMeData['fullName']) ? $user['full_name'] : $updateMeData['fullName'],
            'email' => empty($updateMeData['email']) || !isset($updateMeData['email']) ? $user['email'] : $updateMeData['email'],
            'phone' => empty($updateMeData['phone']) || !isset($updateMeData['phone']) ? $user['phone'] : $updateMeData['phone'],
            'address' => empty($updateMeData['address']) || !isset($updateMeData['address']) ? $user['address'] : $updateMeData['address'],
        ];

        self::$userRepo->update($updateMeRequest, $id);

        return null;
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
            throw new \Exception("Người dùng không tồn tại", BaseHTTPResponse::$NOT_FOUND);
        }

        // kiểm tra refresh token có khớp với refresh token trong CSDL
        if ($refreshToken != $user->refresh_token) {
            throw new \Exception("Token không hợp lệ", BaseHTTPResponse::$UNAUTHORIZED);
        }

        // tạo token cho thành viên
        $result = self::generateToken($user);
        return $result;
    }
}
