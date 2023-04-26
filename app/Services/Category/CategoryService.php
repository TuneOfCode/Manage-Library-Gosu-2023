<?php

namespace App\Services\Category;

use App\Constants\MessageConstant;
use App\Http\Filters\V1\Category\CategoryFilter;
use App\Http\Responses\BaseHTTPResponse;
use App\Repositories\Category\ICategoryRepository;
use Illuminate\Http\Request;

class CategoryService implements ICategoryService {
    /**
     * Đối tượng repository
     */
    private static ICategoryRepository $categoryRepo;
    /**
     * Đối tượng lọc dữ liệu
     */
    private static CategoryFilter $filter;
    /**
     * Hàm khởi tạo
     */
    public function __construct(ICategoryRepository $categoryRepo) {
        self::$categoryRepo = $categoryRepo;
        self::$filter = new CategoryFilter();
    }
    /**
     * Dịch vụ lấy danh sách loại sách
     */
    public static function getAllCategories(Request $request) {
        // xử lý request khi có query
        $query = self::$filter->transform($request);

        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra danh sách loại sách
        $result = self::$categoryRepo->findAll($query, $relations, 10);
        return $result;
    }
    /**
     * Dịch vụ lấy thông tin một loại sách
     */
    public static function getACategory(Request $request, mixed $id) {
        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra chi tiết gói ưu đãi
        $result = self::$categoryRepo->findById($id, $relations);
        if (empty($result)) {
            throw new \Exception(
                MessageConstant::$CATEGORY_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $result;
    }
    /**
     * Dịch vụ tạo mới loại sách
     */
    public static function createCategory(Request $request) {
        $newCategory = self::$categoryRepo->create($request->all());
        $result = self::$categoryRepo->findById($newCategory['id']);
        return $result;
    }
    /**
     * Dịch vụ cập nhật thông tin loại sách
     */
    public static function updateCategory(Request $request, mixed $id) {
        $category = self::$categoryRepo->findById($id);
        if (empty($category)) {
            throw new \Exception(
                MessageConstant::$CATEGORY_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        self::$categoryRepo->update([
            'name' => $request->name ?? $category['name'],
        ], $id);
        return self::$categoryRepo->findById($id);
    }
    /**
     * Dịch vụ xóa loại sách
     */
    public static function deleteCategory(mixed $id) {
        $category = self::$categoryRepo->findById($id);
        if (empty($category)) {
            throw new \Exception(
                MessageConstant::$CATEGORY_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // xoá loại sách
        self::$categoryRepo->destroy($id);
        return null;
    }
}
