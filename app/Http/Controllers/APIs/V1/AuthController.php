<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\BaseResponse;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Thuộc tính dịch vụ xử lý xác thực thành viên
     */
    private AuthService $authService;
    /**
     * Hàm tạo
     */
    public function __construct() {
        $this->authService = new AuthService();
    }
    /**
     * Điều hướng về đăng ký thành viên mới
     */
    public function register(RegisterRequest $registerData) {
        Log::info("***** Đăng ký thành viên mới *****");
        try {
            $newUser = [
                "full_name" => $registerData["full_name"],
                "email" => $registerData["email"],
                'username' => $registerData["username"],
                'password' => Hash::make($registerData['password']), // Hash::make('password
            ];
            $newUser = $this->authService->register($newUser);
            Log::info($registerData);
            Log::info($newUser);
            $data = [
                "id" => $newUser["id"],
                'tokenType' => 'Bearer',
                "token" => $newUser->createToken('authToken')->plainTextToken,
            ];
            return $this->success($registerData, $data, "Đăng ký thành công!");
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return $this->error($registerData, $error, "Đăng ký thất bại!");
        }
    }
    /**
     * Điều hướng về bản thân thành viên hiện tại
     */
    public function me() {
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
