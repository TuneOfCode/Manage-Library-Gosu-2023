<?php

namespace App\Services\Category;

use Illuminate\Http\Request;

interface ICategoryService {
    /**
     * Dịch vụ lấy danh sách loại sách
     */
    public static function getAllCategories(Request $request);
    /**
     * Dịch vụ lấy thông tin một loại sách
     */
    public static function getACategory(Request $request, mixed $id);
    /**
     * Dịch vụ tạo mới loại sách
     */
    public static function createCategory(Request $request);
    /**
     * Dịch vụ cập nhật thông tin loại sách
     */
    public static function updateCategory(Request $request, mixed $id);
    /**
     * Dịch vụ xóa loại sách
     */
    public static function deleteCategory(mixed $id);
}
