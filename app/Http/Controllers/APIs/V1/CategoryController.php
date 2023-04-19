<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use App\Services\V1\CategoryQuery;
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
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $listOfCategory = $this->categoryRepo->findAll(10);
        // return $this->success($request, $listOfCategory,"Lấy ra tất cả loại sách");
        $filter = new CategoryQuery();
        $queryItems = $filter->transform($request);

        if(count($queryItems)==0){
            return new CategoryResourceCollection(Category::paginate());
        }else{
            return new CategoryResourceCollection(Category::where($queryItems)->paginate());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        return new CategoryResource(Category::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $category = $this->categoryRepo->findOne($id);
        if(empty($category)){
            return $this->error($request,"Loại sách không tồn tại");
        }
        return $this->success($request,$category,"Lấy thành công loại sách yêu cầu ");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        if(empty($category)){
            return $this->error($request,"Loại sách không tồn tại");
        }
        return $this->success($request,$category,"Cập nhập loại sách yêu cầu thành công ");
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
