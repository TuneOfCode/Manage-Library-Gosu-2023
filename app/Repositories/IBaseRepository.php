<?php

namespace App\Repositories;

interface IBaseRepository {
    /**
     * Hiển thị tất cả bản ghi
     */
    public function findAll(int $pageSize);
    /**
     * Lấy ra chi tiết một bản ghi thông qua ID
     */
    public function findOne(mixed $id);
    /**
     * Tạo mới một bản ghi
     */
    public function create(mixed $attributes);
    /**
     * Cập nhật một bản ghi
     */
    public function update(mixed $attributes, mixed $id);
    /**
     * Xóa một bản ghi
     */
    public function destroy(mixed $id); 
}