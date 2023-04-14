<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Resources\CategoryResource;
use App\Http\Responses\BaseResponse;
use App\Models\Category;
use App\Http\Requests\V1\Category\StoreCategoryRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
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
        $listOfCategory = $this->categoryRepo->findAll(10);
        return $this->success($request, $listOfCategory,"Lấy ra tất cả loại sách");
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
    public function update(Request $request, Category $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
