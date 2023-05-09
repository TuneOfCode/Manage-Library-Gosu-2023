<?php

namespace App\Services\Package;

use App\Constants\MessageConstant;
use App\Constants\RoleConstant;
use App\Http\Filters\V1\Package\PackageFilter;
use App\Http\Responses\BaseHTTPResponse;
use App\Repositories\Package\IPackageRepository;
use App\Repositories\User\IUserRepository;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageService implements IPackageService {
    /**
     * Đối tượng repository
     */
    private static IPackageRepository $packageRepo;
    private static IUserRepository $userRepo;
    /**
     * Đối tượng lọc dữ liệu
     */
    private static PackageFilter $filter;
    /**
     * Hàm khởi tạo
     */
    public function __construct(IPackageRepository $packageRepo, IUserRepository $userRepo) {
        self::$packageRepo = $packageRepo;
        self::$userRepo = $userRepo;
        self::$filter = new PackageFilter();
    }
    /**
     * Dịch vụ lấy tất cả gói ưu đãi
     */
    public static function getAllPackages(Request $request) {
        // xử lý request khi có query
        $query = self::$filter->transform($request);

        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // xử lý nếu có sắp xếp
        $column = $request->column ?? 'id';
        $sortType = $request->sortType ?? 'asc';
        $limit = $request->limit ?? 10;

        // lấy ra danh sách gói ưu đãi với admin
        if (!empty(Auth::user()) && Auth::user()->hasRole(RoleConstant::$ADMIN)) {
            $result = self::$packageRepo->findAll(
                $query,
                $relations,
                $column,
                $sortType,
                $limit
            );
            return $result;
        }

        // lấy ra danh sách những cuốn sách được cho phép
        // hiển thị với thành viên
        $query = array_merge($query, ['is_active' => 1]);
        $result = self::$packageRepo->findAll(
            $query,
            $relations,
            $column,
            $sortType,
            $limit
        );
        return $result;
    }
    /**
     * Dịch vụ lấy chi tiết gói ưu đãi
     */
    public static function getAPackage(Request $request, mixed $id) {
        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra chi tiết gói ưu đãi
        $result = self::$packageRepo->findById($id, $relations);
        if (
            empty($result)
            || (!$result['is_active'] && !Auth::check()) // khách
            || (!$result['is_active']
                && Auth::check() && Auth::user()->hasRole(RoleConstant::$MEMBER)) // thành viên
        ) {
            throw new \Exception(
                MessageConstant::$PACKAGE_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // trường hợp gói ưu đãi bị khoá
        if (
            !$result['is_active']
            && Auth::user()->hasRole(RoleConstant::$MEMBER)
        ) {
            throw new \Exception(
                MessageConstant::$PACKAGE_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $result;
    }
    /**
     * Dịch vụ tạo gói ưu đãi
     */
    public static function createPackage(Request $request) {
        $newPackage = self::$packageRepo->create($request->all());
        $result = self::$packageRepo->findById($newPackage['id']);
        return $result;
    }
    /**
     * Dịch vụ cập nhật gói ưu đãi
     */
    public static function updatePackage(Request $request, mixed $id) {
        $method = $request->getMethod();
        $updatePackageData = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'description' => $request->input('description'),
            'is_active' => $request->input('isActive')
        ];

        // kiểm tra gói ưu đãi có tồn tại không
        $package = self::$packageRepo->findById($id);
        if (empty($package)) {
            throw new \Exception(
                MessageConstant::$PACKAGE_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        $updatePackageData = [
            'name' => empty($updatePackageData['name'])
                ? $package['name']
                : $updatePackageData['name'],
            'type' => empty($updatePackageData['type'])
                ? $package['type']
                : $updatePackageData['type'],
            'price' => empty($updatePackageData['price'])
                ? $package['price']
                : $updatePackageData['price'],
            'discount' => empty($updatePackageData['discount'])
                ? $package['discount']
                : $updatePackageData['discount'],
            'description' => empty($updatePackageData['description'])
                ? $package['description']
                : $updatePackageData['description'],
            'is_active' => $updatePackageData['is_active'] === $package['is_active']
                ? $package['is_active']
                : $updatePackageData['is_active'],
        ];
        $updatePackageData['is_active'] =
            !empty($updatePackageData['is_active'])
            && $updatePackageData['is_active'] === "true"
            || $updatePackageData['is_active'] === "1"
            ? 1
            : 0;

        if ($method == 'PUT') {
            self::$packageRepo->update($updatePackageData, $id);
            $package = self::$packageRepo->findById($id);
            return $package;
        }

        self::$packageRepo->update($updatePackageData, $id);
        return self::$packageRepo->findById($id);
    }
    /**
     * Dịch vụ xóa gói ưu đãi
     */
    public static function deletePackage(mixed $id) {
        $package = self::$packageRepo->findById($id);
        if (empty($package)) {
            throw new \Exception(
                MessageConstant::$PACKAGE_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // trường hợp xoá gói ưu đãi đang được sử dụng
        $users = self::$userRepo->findAll(['package_id' => $id]);
        if (
            empty($users)
            || count($users) > 0
            || $package['price'] == 0
        ) {
            throw new \Exception(
                MessageConstant::$PACKAGE_IS_USING,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // xoá gói ưu đãi
        self::$packageRepo->destroy($id);
        return null;
    }
    /**
     * Dịch vụ đăng ký gói ưu đãi
     */
    public static function registerPackage(Request $request, mixed $id) {
        $package = self::$packageRepo->findById($id);
        if (empty($package) || $package['price'] == 0 || $package['is_active'] == 0) {
            throw new \Exception(
                MessageConstant::$PACKAGE_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        $user = Auth::user();
        // kiểm tra thành viên đã đăng ký gói ưu đãi này chưa
        if ($user['package_id'] == $id) {
            throw new \Exception(
                MessageConstant::$PACKAGE_ALREADY_REGISTERED,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // kiểm tra số dư tài khoản của thành viên có đủ để đăng ký gói ưu đãi không
        if ($user['balance'] < $package['price']) {
            throw new \Exception(
                MessageConstant::$NOT_ENOUGH_BALANCE,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // trừ tiền trong tài khoản của thành viên
        $newBalance = $user['balance'] - $package['price'];

        // nếu có giảm giá
        if ($package['discount'] > 0) {
            $newBalance = $newBalance - ($package['discount'] * $package['price'] / 100);
        }

        // cập nhật lại gói và số dư tài khoản của thành viên
        self::$userRepo->update([
            'package_id' => $id,
            'balance' => $newBalance
        ], $user['id']);
        $result = self::$userRepo->findById($user['id']);
        return $result;
    }
}
