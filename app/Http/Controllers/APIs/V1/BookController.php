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
use App\Repositories\Book\BookRepository;
use App\Services\Book\BookService;
use App\Services\V1\BookQuery;
use Illuminate\Http\Request;


class BookController extends Controller
{
     /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * [Test] Thuộc tính repo
     */
    private BookRepository $bookRepo;
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
            // $listOfBooks = $this->bookRepo->findAll(10);
            // return $this->success($request, $listOfBooks,"Lấy ra tất cả sách!");
            $filter = new BookFilter();
            $queryItems = $filter->transform($request);
            // dd($queryItems);
            // dd(Book::where('name', 'like', '%ce%')->paginate(10));

            // if(count($queryItems)==0){
            //     // return new BookResourceCollection(Book::paginate());
            // }else{
            //     return new BookResourceCollection(Book::where($queryItems)->paginate());
            // }
            $data = BookService::getAllBook($queryItems);
            dd($data)
;            // return $data;
            return $this->success($request, $data, "Lấy tất cả sách thành công");
        } catch (\Throwable $error) {
            return $this->error($request, $error);
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
    public function store(StoreBookRequest $request)
    {
        return new BookResource(Book::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $book = $this->bookRepo->findOne($id);
        if(empty($book)){
            return $this->error($request,"Không tìm thấy sách yêu cầu");
        }
        return $this->success($request,$book,"Tồn tại sách bạn tìm kím");
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book )
    {
        $book->update($request->all());
        if(empty($book)){
            return $this->error($request,"Loại sách không tồn tại");
        }
        return $this->success($request,$book,"Cập nhập thành công loại sách yêu cầu ");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if(!$book){
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
            }
            $book->delete();
    
            return response()->json(['message' => 'Xóa sản phẩm thành công'],200);
    }
    /**
     * Search
     * 
     */
    public function search(Request $request) {
        $keyword = $request->input('keyword');

        $products = Book::search($keyword)
                           ->get();
    
        return response()->json([
            'data' => $products
        ]);
    }
}
