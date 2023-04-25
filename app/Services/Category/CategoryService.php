<?php

namespace App\Services\Category;


use App\Repositories\Category\ICategoryRepository;
use App\Repositories\IBaseRepository;
use App\Services\Category\ICategoryService;

class CategoryService implements ICategoryService
{
    /**
     * 
     */
    private static ICategoryRepository $categoryRepo;
    /**
     * 
     */
    public function __construct(ICategoryRepository $categoryRepo) {
        self::$categoryRepo = $categoryRepo;
    }
    public static function getAllCategory($attributes)
    {
        $result = self::$categoryRepo->findAll($attributes, 10);
        return $result;
    }

    public static function getByIdCategory($id)
    {
        $result = self::$categoryRepo->findById($id);
        //$status = $result->status;
        // !$status && Auth::user()->hasRole('member')
        // trạng thái sách bị khóa và vai trò là người dùng 
        // thì không thể truy cập được
        // if (!$status ) {
        //     throw new \Exception("Không tìm thấy thể loại sách yêu cầu", 404);       
        // }
        return $result;
    }

    public static function createCategory($data)
    {
        // Kiểm tra tển sách và tác giả có trùng trong CSDL hay không
        // điều kiện để trùng sách là có cùng tên và tác giả của sách đó
        $oldCategory = self::$categoryRepo->findOne([
            'name' => $data['name'],
        ]);
        $newCategory = [];
        // không trùng
        if (empty($oldCategory)) {
            $newCategory = self::$categoryRepo->create($data);
            
        } else { // trùng
            // sửa lại số lượng
           //messenger thông báo Category đã được tạo từ trước
        }
        $newCategory = self::$categoryRepo->findById($data['name']);
        return $newCategory;
    }
    /**
     * Hàm câp nhập sách
     */
    public static function updateCategory($data)
    {
        $updateData = $data->toArray();
        $method = $data->getMethod();
        if ($method === 'PATCH') {

        }
        $id = 0;// $updateData['id'];
        $isUpdated = self::$categoryRepo->update($updateData, $id);
        if (!$isUpdated) {
            throw new \Exception("Cập nhật thất bại", 400);                  
        }
        $category = self::$categoryRepo->findById($id);
        return $category;
    }

    public static function deleteCategory($id)
    {
        self::$categoryRepo->destroy($id);
        return true;
    }
}