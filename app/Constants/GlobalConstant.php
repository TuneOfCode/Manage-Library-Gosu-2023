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
     * Liên kết cơ sở xác thực email
     */
    static string $BASE_VERIFY_EMAIL = "http://localhost:8000/api/v1/auth/verify-email";
    /**
     * File chứa oauth-public key
     */
    static string $OAUTH_PUBLIC_KEY = "oauth-public.key";
    /**
     * Thuật toán mã hoá
     */
    static string $ALGORITHM = "RS256";
}
