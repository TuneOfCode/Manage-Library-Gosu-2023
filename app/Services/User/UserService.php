<?php

namespace App\Services\User;

use App\Constants\MessageConstant;
use App\Http\Filters\V1\User\UserFilter;
use App\Http\Responses\BaseHTTPResponse;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class UserService implements IUserService {
    /**
     * Đối tượng repository
     */
    private static IUserRepository $userRepo;
    /**
     * Đối tượng lọc dữ liệu
     */
    private static UserFilter $filter;
    /**
     * Hàm khởi tạo
     */
    public function __construct(IUserRepository $userRepo) {
        self::$userRepo = $userRepo;
        self::$filter = new UserFilter();
        new AuthService(new UserRepository());
    }
    /**
     * Dịch vụ lấy tất cả người dùng 
     * @param Request $request
     */
    public static function getAllUsers(Request $request) {
        // xử lý request khi có query
        $query = self::$filter->transform($request);

        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // xử lý nếu có sắp xếp
        $column = $request->column ?? 'id';
        $sortType = $request->sortType ?? 'asc';
        $limit = $request->limit ?? 10;

        // lấy ra danh sách người dùng
        $result = self::$userRepo->findAll(
            $query,
            $relations,
            $column,
            $sortType,
            $limit
        );
        return $result;
    }
    /**
     * Dịch vụ lấy chi tiết người dùng
     * @param Request $request
     * @param mixed $id
     */
    public static function getAUser(Request $request, mixed $id) {
        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra danh sách người dùng
        $result = self::$userRepo->findById($id, $relations);
        if (empty($result)) {
            throw new \Exception(
                MessageConstant::$USER_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $result;
    }
    /**
     * Dịch vụ xóa người dùng
     */
    public static function deleteUser(mixed $id) {
        // kiểm tra người dùng có tồn tại không
        $user = self::$userRepo->findById($id);
        if (empty($user)) {
            throw new \Exception(
                MessageConstant::$USER_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // xóa người dùng
        $isDeleted = self::$userRepo->destroy($id);
        if (!$isDeleted) {
            throw new \Exception(
                MessageConstant::$DELETE_USER_FAILED,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $isDeleted;
    }
    /**
     * Dịch vụ khoá tài khoản người dùng
     */
    public static function lockUser(mixed $id) {
        // kiểm tra người dùng có tồn tại không
        $user = self::$userRepo->findById($id);
        if (empty($user)) {
            throw new \Exception(
                MessageConstant::$USER_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // khoá tài khoản của người dùng
        $isLocked = self::$userRepo->update([
            'status' => 0,
        ], $id);
        if (!$isLocked) {
            throw new \Exception(
                MessageConstant::$LOCK_USER_FAILED,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $isLocked;
    }
    /**
     * Dịch vụ mở khoá tài khoản người dùng
     */
    public static function unlockUser(mixed $id) {
        // kiểm tra người dùng có tồn tại không
        $user = self::$userRepo->findById($id);
        if (empty($user)) {
            throw new \Exception(
                MessageConstant::$USER_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // Mở khoá tài khoản của người dùng
        $isUnlocked = self::$userRepo->update([
            'status' => 1,
        ], $id);
        if (!$isUnlocked) {
            throw new \Exception(
                MessageConstant::$UNLOCK_USER_FAILED,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $isUnlocked;
    }
}
