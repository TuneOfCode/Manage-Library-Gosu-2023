<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements IBaseRepository {
    /**
     * Thuộc tính dùng cho một mô hình lớp
     */
    protected Model $_model;
    /**
     * 
     */
    public function __construct() {
        $this->setModel();
    }
    /**
     * Hàm getter model
     */
    abstract public function getModel();
    /**
     * Hàm setter model
     */
    public function setModel() {
        return $this->_model = app()->make($this->getModel());
    }
    /**
     * Lấy ra chi tiết một bản ghi thông qua mảng điều kiện
     * @param mixed $attributes
     * @return mixed
     */
    public function findOne(mixed $attributes) {
        return $this->_model->where($attributes)->first();
    }
    /**
     * Hiển thị tất cả bản ghi
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findAll(int $pageSize = 10) {
        return $this->_model->paginate($pageSize);
    }
    /**
     * Lấy ra chi tiết một bản ghi
     * @param mixed $id 
     * @return mixed
     */
    public function findById(mixed $id) {
        return $this->_model->find($id);
    }
    /**
     * Tạo môt bản ghi
     * @param mixed $attributes
     * @return mixed
     */
    public function create(mixed $attributes) {
        return $this->_model->create($attributes);
    }
    /**
     * Cập nhật môt bản ghi
     * @param mixed $attributes
     * @param mixed $id
     * @return boolean|mixed
     */
    public function update(mixed $attributes, mixed $id) {
        $item = $this->_model::find($id);
        if (empty($item) || !isset($item)) {
            return false;
        }

        foreach ($attributes as $key => $value) {
            $item[$key] = $value;
        }

        $item->save();
        return true;
    }
    /**
     * Xóa một bản ghi
     * @param mixed $id
     * @return boolean|mixed 
     */
    public function destroy(mixed $id) {
        $item = $this->findById($id);
        if (empty($item) || !isset($item)) {
            return false;
        }

        $item->delete();
        return $item;
    }
}
