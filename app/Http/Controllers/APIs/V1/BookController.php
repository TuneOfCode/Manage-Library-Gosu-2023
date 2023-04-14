<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Book\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Responses\BaseResponse;
use App\Models\Book;
use App\Repositories\Book\BookRepository;
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
        $this->bookRepo = new BookRepository();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $listOfBooks = $this->bookRepo->findAll(10);
        return $this->success($request, $listOfBooks,"Lấy ra tất cả sách!");
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
    public function update(Request $request, Book $book )
    {
        $book->update($request->all());
        
        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response(null, 204);
    }
}
