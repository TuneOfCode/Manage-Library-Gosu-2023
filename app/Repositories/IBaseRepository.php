<?php

namespace App\Repositories;

interface IBaseRepository {
    /**
     * Hiển thị tất cả bản ghi
     */
    public function findAll(
        array $attributes,
        array $relations = [],
        string $column = "id",
        string $sortType = "asc",
        int $pageSize = 10
    );
    /**
     * Lấy ra chi tiết một bản ghi thông qua mảng điều kiện
     */
    public function findOne(mixed $attributes, array $relations = []);
    /**
     * Lấy ra chi tiết một bản ghi thông qua ID
     */
    public function findById(mixed $id, array $relations = []);
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
