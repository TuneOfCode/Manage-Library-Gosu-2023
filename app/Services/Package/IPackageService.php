<?php

namespace App\Services\Package;

use Illuminate\Http\Request;

interface IPackageService {
    /**
     * Dịch vụ lấy tất cả gói ưu đãi
     */
    public static function getAllPackages(Request $request);
    /**
     * Dịch vụ lấy chi tiết gói ưu đãi
     */
    public static function getAPackage(Request $request, mixed $id);
    /**
     * Dịch vụ tạo gói ưu đãi
     */
    public static function createPackage(Request $request);
    /**
     * Dịch vụ cập nhật gói ưu đãi
     */
    public static function updatePackage(Request $request, mixed $id);
    /**
     * Dịch vụ xóa gói ưu đãi
     */
    public static function deletePackage(mixed $id);
    /**
     * Dịch vụ đăng ký gói ưu đãi
     */
    public static function registerPackage(Request $request, mixed $id);
}
