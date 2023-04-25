<?php

namespace App\Http\Controllers\APIs\V1;

use App\Filters\V1\BookFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Book\StoreBookRequest;
use App\Http\Requests\V1\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\Book\BookRepository;
use App\Services\Book\BookService;
use Illuminate\Http\Request;


class BookController extends Controller
{
     /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        // $this->bookRepo = new BookRepository();
        new BookService(new BookRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // lọc 
            $filter = new BookFilter();
            $queryItems = $filter->transform($request);
            
            // gọi dịch vụ lấy ra tất cả các sách
            $data = BookService::getAllBook($queryItems);

            // xử lý nếu có request type
            $typeRequest = $request->get('type');
            if ($typeRequest === "new") {
                $data = BookService::newBooks();
            } else if ($typeRequest === "old") {
                $data = BookService::oldBooks();
            } else {
                // chuyển về dạng
                $data = new BookResourceCollection($data);
            }
            
            return $this->success($request, $data, "Lấy tất cả sách thành công");
        } catch (\Throwable $error) {
            return $this->error($request, $error, $error->getMessage() || "Lấy tất cả sách thất bại");
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        // return new BookResource(Book::create($request->all()));
        try {
            // gọi dịch vụ tạo sách
            $data = BookService::createBook($request->toArray());
            return $this->success($request, $data, "Tạo mới sách thành công");
        } catch (\Throwable $error) {
            return $this->error($request, $error, $error->getMessage() || "Lấy tất cả sách thất bại");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            // gọi dịch vụ lấy ra chi tiết một sách
            $data = BookService::getByIdBook($id);
            // chuyển về dạng
            $data = new BookResource($data);
            return $this->success($request, $data, "Lấy chi tiết sách thành công");
        } catch (\Throwable $error) {
            return $this->error($request, $error, $error->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request)
    {
        try {
            // gọi dịch vụ cập nhật một sách
            $data = BookService::updateBook($request);
            // chuyển về dạng
            $data = new BookResource($data);
            return $this->success($request, $data, "Cập nhật sách thành công");
        } catch (\Throwable $error) {
            return $this->error($request, $error, $error->getMessage());
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
    /**
     * Search
     * 
     */
    public function search(Request $request) {
        $keyword = $request->input('keyword');

        $products = Category::search($keyword)
                           ->get();
    
        return response()->json([
            'data' => $products
        ]);
    }
    public function oldBooks()
    {
        $books = BookService::oldBooks();
        return response()->json($books);
    }
    public function newBooks()
    {
        $books = BookService::newBooks();
        return response()->json($books);
    }
}
