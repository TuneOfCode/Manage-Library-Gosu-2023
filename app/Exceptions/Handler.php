<?php

namespace App\Exceptions;

use App\Constants\GlobalConstant;
use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    /**
     * Xử lý ngoại lệ không được uỷ quyền
     */
    protected function unauthenticated($request, AuthenticationException $error) {
        $statusCode = 401;
        return response()->json([
            'status' => BaseHTTPResponse::$HTTP[$statusCode],
            'statusCode' => $statusCode,
            'message' => $error->getMessage(),
            'error' => [
                'code' => $statusCode,
                'message' => $error->getMessage(),
                'content' => null,
                // 'file' => $error->getFile(),
                // 'line' => $error->getLine(),
            ],
            'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
            'path' => $request->getRequestUri()
        ], $statusCode);
    }
    /**
     * Xử lý ngoại lệ yêu cầu từ client
     */
    public function convertValidationExceptionToResponse(ValidationException $error, $request) {
        $statusCode = 422;
        return response()->json([
            'status' => BaseHTTPResponse::$HTTP[$statusCode],
            'statusCode' => $statusCode,
            'message' => $error->getMessage(),
            'error' => [
                'code' => $statusCode,
                'message' => $error->getMessage(),
                'content' => $error->errors(),
                // 'file' => $error->getFile(),
                // 'line' => $error->getLine(),
            ],
            'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
            'path' => $request->getRequestUri()
        ], $statusCode);
    }
}
