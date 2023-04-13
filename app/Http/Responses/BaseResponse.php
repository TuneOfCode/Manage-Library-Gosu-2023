<?php

namespace App\Http\Responses;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait BaseResponse {
    /**
     * Hàm định nghĩa cấu trúc trả về của API khi ở trạng thái thành công
     */
    public function success(Request $request, mixed $data = null, 
        $message = null, $messageCode = "OK", $statusCode = 200): JsonResponse {
        if (is_array($data) || $data instanceof LengthAwarePaginator) {
            return response()->json([
                'status' => $messageCode,
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
                'time' => Carbon::now()->format('d-m-Y H:i:s A'),
                'path' => $request->getUri()
            ]);
        }

        return response()->json([
            'status' => $messageCode,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
            'time' => Carbon::now()->format('d-m-Y H:i:s A'),
            'path' => $request->getRequestUri()
        ]);
    }
    /**
     * Hàm định nghĩa cấu trúc trả về của API khi ở trạng thái thất bại
     */
    public function error(Request $request, mixed $error = null, 
        $message = null, $messageCode = "Internal Server Error", $statusCode = 500): JsonResponse {

        return response()->json([
            'status' => $messageCode,
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
            'time' => Carbon::now()->format('d-m-Y H:i:s A'),
            'path' => $request->getRequestUri()
        ]);
    }
}