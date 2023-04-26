<?php

namespace App\Constants;

class GlobalConstant {
    /**
     * Tên middleware xác thực
     */
    static string $AUTH_MIDDLEWARE = "auth:api";
    /**
     * Tên token xác thực
     */
    static string $AUTH_TOKEN = "authToken";
    /**
     * Thời gian chuẩn UTC
     */
    static string $TIMEZONE_UTC = "UTC";
    /**
     * Định dạng chuyển từ timestamp sang datetime
     */
    static string $FORMAT_TIMESTAMP = "Y-m-d\TH:i:s.u\Z";
    /**
     * Định dạng ngày tháng năm
     */
    static string $FORMAT_DATETIME = "d/m/Y H:i:s A";
    /**
     * Định dạng ngày tháng năm trong CSDL
     */
    static string $FORMAT_DATETIME_DB = "Y-m-d H:i:s";
    /**
     * Định dạng múi giờ
     */
    static string $FORMAT_TIMEZONE = "Asia/Ho_Chi_Minh";
    /**
     * Liên kết cơ sở gửi lại xác thực email
     */
    static string $BASE_RESEND_OTP_EMAIL = '/api/v1/auth/resend-otp-email'; // {DOMAIN}/api/v1/auth/resend-otp-email
    /**
     * Liên kết cơ sở xác thực email
     */
    static string $BASE_VERIFY_EMAIL = '/api/v1/auth/verify-email'; // {DOMAIN}/api/v1/auth/verify-email
    /**
     * File chứa oauth-public key
     */
    static string $OAUTH_PUBLIC_KEY = "oauth-public.key";
    /**
     * File chứa oauth-private key
     */
    static string $OAUTH_PRIVATE_KEY = "oauth-private.key";
    /**
     * Thời gian sống của refresh token
     */
    static mixed $REFRESH_TOKEN_LIFE_TIME = 60 * 60 * 24 * 30; // 30 ngày
    /**
     * Thuật toán mã hoá
     */
    static string $ALGORITHM = "RS256";
    /**
     * Loại token
     */
    static string $TYPE_TOKEN = "Bearer";
    /**
     * Đặt giá trị mặc định cho guard trong phân quyền
     */
    static string $GUARD_API = "api";
    /**
     * Vai trò quản trị viên
     */
    static string $ROLE_ADMIN = "role:admin";
    /**
     * Vai trò thành viên
     */
    static string $ROLE_MEMBER = "role:member";
    /**
     * Nhãn sách
     */
    static string $LABEL_NORMAL = "normal";
    static string $LABEL_VIP = "vip";
    static string $LABEL_HOT = "hot";
    static string $LABEL_TRENDING = "trending";
}
