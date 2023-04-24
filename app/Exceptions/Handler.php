<?php

namespace App\Exceptions;

use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
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
        return $this->error($request, $error, $error->getMessage(), BaseHTTPResponse::$UNAUTHORIZED);
    }
    /**
     * Xử lý ngoại lệ yêu cầu từ client
     */
    public function convertValidationExceptionToResponse(ValidationException $error, $request) {
        return $this->error($request, $error, $error->getMessage(), BaseHTTPResponse::$UNPROCESSABLE_ENTITY);
    }
    /**
     * Xử lý ngoại lệ không được phép
     */
    public function render($request, Throwable $error) {
        if ($error instanceof UnauthorizedException) {
            return $this->error($request, $error, $error->getMessage(), BaseHTTPResponse::$FORBIDDEN);
        }

        return parent::render($request, $error);
    }
}
