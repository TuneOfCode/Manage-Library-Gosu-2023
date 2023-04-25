<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Resources\V1\User\UserResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        new UserService(new UserRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        Log::info("***** Lấy tất cả người dùng *****");
        try {
            $data = new UserResourceCollection(UserService::getAllUsers($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_USER_FAILED
            );
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id) {
        Log::info("***** Lấy chi tiết một người dùng *****");
        try {
            $data = new UserResource(UserService::getAUser($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_USER_FAILED
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id) {
        Log::info("***** Xóa một người dùng *****");
        try {
            UserService::deleteUser($id);
            return $this->success(
                $request,
                null,
                MessageConstant::$DELETE_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_USER_FAILED
            );
        }
    }
    /**
     * Khoá một người dùng
     */
    public function lock(Request $request, string $id) {
        Log::info("***** Khoá tài khoản của một người dùng *****");
        try {
            UserService::lockUser($id);
            return $this->success(
                $request,
                null,
                MessageConstant::$LOCK_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$LOCK_USER_FAILED
            );
        }
    }
    /**
     * Mở khoá một người dùng
     */
    public function unlock(Request $request, string $id) {
        Log::info("***** Mở khoá tài khoản của một người dùng *****");
        try {
            UserService::unlockUser($id);
            return $this->success(
                $request,
                null,
                MessageConstant::$UNLOCK_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UNLOCK_USER_FAILED
            );
        }
    }
}
