<?php

namespace App\Http\Responses;
use App\Constants\GlobalConstant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait BaseResponse {
    /**
     * Hàm định nghĩa cấu trúc trả về của API khi ở trạng thái thành công
     */
    public function success(
        Request $request,
        mixed $data = null,
        $message = null,
        $statusCode = 200,
        $messageCode = "OK"
    ): JsonResponse {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'status' => BaseHTTPResponse::$HTTP[$statusCode] ?? $messageCode,
                'statusCode' => $statusCode,
                'message' => $message,
                'data' => $data->items(),
                'meta' => [
                    'currentPage' => $data->currentPage(),
                    'perPage' => $data->perPage(),
                    'totalPages' => $data->lastPage(),
                    'totalRows' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem()
                ],
                'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
                'path' => $request->getRequestUri()
            ], $statusCode);
        }

        return response()->json([
            'status' => BaseHTTPResponse::$HTTP[$statusCode] ?? $messageCode,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
            'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
            'path' => $request->getRequestUri()
        ], $statusCode);
    }
    /**
     * Hàm định nghĩa cấu trúc trả về của API khi ở trạng thái thất bại
     */
    public function error(
        Request $request,
        mixed $error = null,
        $message = null,
        $statusCode = 500,
        $messageCode = "Internal Server Error"
    ): JsonResponse {
        $statusCode = (empty($error->getCode())
            || $error->getCode() === 0)
            ? $statusCode
            : $error->getCode();
        $message = (empty($error->getMessage()))
            ? $message
            : $error->getMessage();
        $content = null;

        if ($error instanceof ValidationException) {
            $content = $error->errors();
        }
        return response()->json([
            'status' => BaseHTTPResponse::$HTTP[$statusCode] ?? $messageCode,
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => [
                'code' => $statusCode,
                'message' => $message,
                'content' => $content,
                'file' => $error->getFile(),
                'line' => $error->getLine(),
            ],
            'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
            'path' => $request->getRequestUri()
        ], $statusCode);
    }
}