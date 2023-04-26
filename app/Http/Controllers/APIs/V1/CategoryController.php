<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Category\CategoryResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Repositories\Category\CategoryRepository;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        new CategoryService(new CategoryRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        Log::info("***** Lấy tất cả danh mục loại sách *****");
        try {
            $data = new CategoryResourceCollection(CategoryService::getAllCategories($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_CATEGORY_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_CATEGORY_FAILED
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request) {
        Log::info("***** Tạo mới một loại sách *****");
        try {
            $data = new CategoryResource(CategoryService::createCategory($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$CREATE_CATEGORY_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CREATE_CATEGORY_FAILED
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, mixed $id) {
        Log::info("***** Lấy chi tiết một loại sách *****");
        try {
            $data = new CategoryResource(CategoryService::getACategory($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_CATEGORY_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_CATEGORY_FAILED
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, mixed $id) {
        Log::info("***** Cập nhật một loại sách *****");
        try {
            $data = new CategoryResource(CategoryService::updateCategory($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPDATE_CATEGORY_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPDATE_CATEGORY_FAILED
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, mixed $id) {
        Log::info("***** Xoá một loại sách *****");
        try {
            CategoryService::deleteCategory($id);
            return $this->success(
                $request,
                null,
                MessageConstant::$DELETE_CATEGORY_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_CATEGORY_FAILED
            );
        }
    }
}
