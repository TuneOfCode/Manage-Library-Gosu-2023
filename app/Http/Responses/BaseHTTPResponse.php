<?php

namespace App\Http\Responses;

class BaseHTTPResponse {
    /**
     * Mã và thông báo trạng thái trả về của HTTP
     */
    static public array $HTTP = [
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        204 => "No Content",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        402 => "Payment Required",
        405 => "Method Not Allowed",
        422 => "Unprocessable Entity",
        500 => "Internal Server Error",
    ];
}
