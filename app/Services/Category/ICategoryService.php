<?php

namespace App\Services\Category;

interface ICategoryService{
    /**
     * Hiện thị tất cả loại sách
     */
    public static function getAllCategory($attributes);
    /**
     * Hiển thị 1 loại sách theo ID
     */
    public static function getByIdCategory($id);
    /**
     * Tạo  loại sách
     */
    public static function createCategory($data);
    /**
     * Cập nhập loại sách
     */
    public static function updateCategory($data);
    /**
     * Xóa 1 loại sách theo ID
     */
    public static function deleteCategory($id);
}