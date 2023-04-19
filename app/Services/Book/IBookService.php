<?php

namespace App\Services\Book;

interface IBookService{
    /**
     * Hiện thị tất cả sách
     */
    public static function getAllBook($attributes);
    /**
     * Hiển thị 1 sách theo ID
     */
    public static function getByIdBook($id);
    /**
     * Tạo  sách
     */
    public static function createBook($data);
    /**
     * Cập nhập sách
     */
    public static function updateBook($id, $data);
    /**
     * Xóa 1 sách theo ID
     */
    public static function deleteBook($id);
}