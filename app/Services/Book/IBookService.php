<?php

namespace App\Services\Book;

use Illuminate\Http\Request;

interface IBookService {
    /**
     * Dịch vụ lấy tất cả sách
     */
    public static function getAllBooks(Request $request);
    /**
     * Dịch vụ lấy chi tiết sách
     */
    public static function getABook(Request $request, mixed $id);
    /**
     * Dịch vụ tạo mới sách
     */
    public static function createBook(Request $request);
    /**
     * Dịch vụ cập nhật sách
     */
    public static function updateBook(Request $request, mixed $id);
    /**
     * Dịch vụ xóa sách
     */
    public static function deleteBook(mixed $id);
}
