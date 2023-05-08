<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BookUser\BorrowBooksRequest;
use App\Http\Requests\V1\BookUser\UpdateBookUserRequest;
use App\Http\Resources\V1\BookUser\BookUserResource;
use App\Http\Resources\V1\BookUser\BookUserResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Repositories\Book\BookRepository;
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
        new BookUserService(
            new BookUserRepository(),
            new BookRepository()
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        Log::info('***** Lấy ra lịch sử danh sách thuê sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::getBookUserList($request)
            );
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
    public function store(BorrowBooksRequest $request) {
        Log::info('***** Mượn nhiều sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::borrowBooks($request)
            );
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
            $data = new BookUserResource(
                BookUserService::getBookUserDetail($request, $id)
            );
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
    public function approve(UpdateBookUserRequest $request) {
        Log::info('***** Duyệt mượn sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::approveBorrowingBooks($request)
            );
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
    public function reject(UpdateBookUserRequest $request) {
        Log::info('***** Từ chối mượn sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::rejectBorrowingBooks($request)
            );
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
    public function cancel(UpdateBookUserRequest $request) {
        Log::info('***** Hủy yêu cầu mượn sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::cancelBorrowingBooks($request)
            );
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
    public function pay(UpdateBookUserRequest $request) {
        Log::info('***** Thanh toán tiền mượn sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::payBorrowingBooks($request)
            );
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
     * Điều hướng xác nhận thành viên đã nhận được sách
     */
    public function confirmReceived(UpdateBookUserRequest $request) {
        Log::info('***** Xác nhận thành viên đã nhận được sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::confirmReceivingBooks($request)
            );
            return $this->success(
                $request,
                $data,
                MessageConstant::$CONFIRM_RECEIVED_BOOKS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CONFIRM_RECEIVED_BOOKS_FAILED
            );
        }
    }

    /**
     * Điều hướng trả sách
     */
    public function confirmReturned(UpdateBookUserRequest $request) {
        Log::info('***** Trả sách *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::returnBooks($request)
            );
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
     * Điều hướng về thanh toán phụ phí
     */
    public function payExtraMoney(UpdateBookUserRequest $request) {
        Log::info('***** Thanh toán phụ phí *****');
        try {
            $data = new BookUserResourceCollection(
                BookUserService::payExtraMoney($request)
            );
            return $this->success(
                $request,
                $data,
                MessageConstant::$PAY_EXTRA_MONEY_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$PAY_EXTRA_MONEY_FAILED
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpdateBookUserRequest $request) {
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
