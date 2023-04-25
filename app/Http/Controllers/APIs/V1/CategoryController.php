<?php

namespace App\Http\Controllers\APIs\V1;

use App\Filters\V1\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
    
class CategoryController extends Controller
{

    /**
     * Định dạng dữ liệu
     */
    use BaseResponse;
    /**
     * Test thuộc tính
     */
    private CategoryRepository $categoryRepo;
    /**
     * Hàm khởi tạo
     */
    public function __construct(){
        $this->categoryRepo = new CategoryRepository;
        new CategoryService(new CategoryRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            //lọc 
            $filter = new CategoryFilter();
            $queryItems = $filter->transform($request);

            $data = CategoryService::getAllCategory($queryItems);
            $data = new CategoryResourceCollection($data);
        
            return $this->success($request,$data,"Lấy tất cả các thể loại sách thành công");
        } catch (\Throwable $erro){
            return $this->error($request, $erro, $erro->getMessage()|| "Lấy tất cả thể loại sách thất bại");
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try{
            $data = CategoryService::createCategory($request->toArray());
            return $this->success($request,$data,"Tạo mới thành công");
        }catch(\Throwable $error){
            return $this->error($request,$error, $error->getMessage() || "Tạo mới thể loại sách thất bại ");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try{
            // gọi hàm lấy ra chi tiết 1 loại sách theo ID
            $data = CategoryService::getByIdCategory($id);
            // chuyển dạng 
            $data = new CategoryResource($data);
            return $this->success($request,$data,"lấy chi tiếc loại sách thành công");
        }catch(\Throwable $error){
            return $this->error($request, $error, $error->getMessage()||"Lấy chi tiết loại sách thất bại");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try{
            // gọi hàm cập nhập sách
            $data = CategoryService::updateCategory($request);
            //chuyển về dạng
            $data = new CategoryResource($data);
            return  $this->success($request, $data, "Cập nhập thành công");
        }catch(\Throwable $error){
            return $this->error($request, $error, $error->getMessage()||"Cập nhập thất bại");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if(!$category){
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
            }
            $category->delete();
    
            return response()->json(['message' => 'Xóa sản phẩm thành công'],200);
    }
}
