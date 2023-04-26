<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\V1\Book\StoreBookRequest;
use App\Http\Requests\V1\Book\UpdateBookRequest;
use App\Http\Resources\V1\Book\BookResource;
use App\Http\Resources\V1\Book\BookResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Repositories\Book\BookRepository;
use App\Services\Book\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        new BookService(new BookRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        Log::info("***** Lấy tất cả sách *****");
        try {
            $data = new BookResourceCollection(BookService::getAllBooks($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_BOOK_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_BOOK_FAILED
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request) {
        Log::info("***** Tạo mới một sách *****");
        try {
            $data = new BookResource(BookService::createBook($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$CREATE_BOOK_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CREATE_BOOK_FAILED
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, mixed $id) {
        Log::info("***** Lấy chi tiết sách *****");
        try {
            $data = new BookResource(BookService::getABook($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_BOOK_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_BOOK_FAILED
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, mixed $id) {
        Log::info("***** Cập nhật một sách *****");
        try {
            $data = new BookResource(BookService::updateBook($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPDATE_BOOK_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPDATE_BOOK_FAILED
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, mixed $id) {
        Log::info("***** Xóa một sách *****");
        try {
            BookService::deleteBook($id);
            return $this->success(
                $request,
                null,
                MessageConstant::$DELETE_BOOK_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_BOOK_FAILED
            );
        }
    }
}
