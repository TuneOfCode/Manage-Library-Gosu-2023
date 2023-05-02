<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResponse;
use App\Repositories\BookUser\BookUserRepository;
use App\Services\BookUser\BookUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookUserController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        new BookUserService(new BookUserRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        Log::info('***** Lấy ra lịch sử danh sách thuê sách *****');
        try {
            $data = BookUserService::getBookUserList($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_BOOK_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_BOOK_USER_FAILED
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        Log::info('***** Mượn nhiều sách *****');
        try {
            $data = BookUserService::borrowBooks($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$BORROW_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$BORROW_BOOKS_FAILED
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id) {
        Log::info('***** Lấy ra thông tin chi tiết lịch sử thuê sách *****');
        try {
            $data = BookUserService::getBookUserDetail($request, $id);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_BOOK_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_BOOK_USER_FAILED
            );
        }
    }

    /**
     * Điều hướng duyệt mượn sách
     */
    public function approve(Request $request) {
        Log::info('***** Duyệt mượn sách *****');
        try {
            $data = BookUserService::approveBorrowingBooks($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$APPROVE_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$APPROVE_BOOKS_FAILED
            );
        }
    }

    /**
     * Điều hướng từ chối mượn sách
     */
    public function reject(Request $request) {
        Log::info('***** Từ chối mượn sách *****');
        try {
            $data = BookUserService::rejectBorrowingBooks($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$REJECT_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$REJECT_BOOKS_FAILED
            );
        }
    }

    /**
     * Điều hướng hủy yêu cầu mượn sách
     */
    public function cancel(Request $request) {
        Log::info('***** Hủy yêu cầu mượn sách *****');
        try {
            $data = BookUserService::cancelBorrowingBooks($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$CANCEL_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CANCEL_BOOKS_FAILED
            );
        }
    }

    /**
     * Điều hướng thanh toán tiền mượn sách
     */
    public function pay(Request $request) {
        Log::info('***** Thanh toán tiền mượn sách *****');
        try {
            $data = BookUserService::payBorrowingBooks($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$PAY_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$PAY_BOOKS_FAILED
            );
        }
    }

    /**
     * Điều hướng trả sách
     */
    public function return(Request $request) {
        Log::info('***** Trả sách *****');
        try {
            $data = BookUserService::returnBooks($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$RETURN_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$RETURN_BOOKS_FAILED
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request) {
        Log::info('***** Xóa lịch sử thuê sách *****');
        try {
            BookUserService::deleteBookUser($request);
            return $this->success(
                $request,
                MessageConstant::$DELETE_BOOK_USER_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_BOOK_USER_FAILED
            );
        }
    }
}
