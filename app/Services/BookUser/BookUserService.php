<?php

namespace App\Services\BookUser;

use App\Constants\MessageConstant;
use App\Http\Filters\V1\BookUser\BookUserFilter;
use App\Http\Responses\BaseHTTPResponse;
use App\Repositories\BookUser\IBookUserRepository;
use Illuminate\Http\Request;

class BookUserService implements IBookUserService {
    /**
     * Đối tượng repository
     */
    private static IBookUserRepository $bookUserRepo;
    /**
     * Đối tượng lọc dữ liệu
     */
    private static BookUserFilter $filter;
    /**
     * Hàm khởi tạo
     */
    public function __construct(IBookUserRepository $bookUserRepo) {
        self::$bookUserRepo = $bookUserRepo;
        self::$filter = new BookUserFilter();
    }
    /**
     * Dịch vụ lấy danh sách sách thuê sách
     */
    public static function getBookUserList(Request $request) {
        // xử lý request khi có query
        $query = self::$filter->transform($request);

        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // xử lý nếu có sắp xếp
        $column = $request->column ?? 'id';
        $sortType = $request->sortType ?? 'asc';
        $limit = $request->limit ?? 10;

        // lấy ra danh sách thuê sách
        $result = self::$bookUserRepo->findAll(
            $query,
            $relations,
            $column,
            $sortType,
            $limit
        );
        return $result;
    }
    /**
     * Dịch vụ lấy thông tin sách thuê sách
     */
    public static function getBookUserDetail(Request $request, mixed $id) {
        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra chi tiết một bản ghi thuê sách
        $result = self::$bookUserRepo->findById($id, $relations);
        if (empty($result)) {
            throw new \Exception(
                MessageConstant::$BOOK_USER_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        return $result;
    }
    /**
     * Dịch vụ cho mượn sách
     */
    public static function borrowBooks(Request $request) {
        //TODO: xử lý logic cho mượn sách

        return [];
    }
    /**
     * Dịch vụ phê duyệt cho mượn sách
     */
    public static function approveBorrowingBooks(Request $request) {
        // TODO: xử lý logic phê duyệt cho mượn sách
        return [];
    }
    /**
     * Dịch vụ từ chối yêu cầu mượn sách
     */
    public static function rejectBorrowingBooks(Request $request) {
        // TODO: xử lý logic từ chối yêu cầu mượn sách
        return [];
    }
    /**
     * Dịch vụ trả tiền thuê sách
     */
    public static function payBorrowingBooks(Request $request) {
        return [];
    }
    /**
     * Dịch vụ xác nhận nhận sách
     */
    public static function confirmReceivingBooks(Request $request) {
        // TODO: xử lý logic xác nhận nhận sách
        return [];
    }
    /**
     * Dich vụ hủy yêu cầu mượn sách
     */
    public static function cancelBorrowingBooks(Request $request) {
        // TODO: xử lý logic hủy yêu cầu mượn sách
        return [];
    }
    /**
     * Dịch vụ trả sách
     */
    public static function returnBooks(Request $request) {
        // TODO: xử lý logic trả sách
        return [];
    }
    /**
     * Dịch vụ kiểm tra sách trả có quá hạn không
     */
    public static function checkOverdueBooks(Request $request) {
        // TODO: xử lý logic kiểm tra sách trả có quá hạn không
    }
    /**
     * Dịch vụ kiểm tra không trả sách
     */
    public static function checkNotReturnBooks(Request $request) {
        // TODO: xử lý logic kiểm tra không trả sách
    }
    /**
     * Dịch vụ xoá thuê sách
     */
    public static function deleteBookUser(Request $request) {
        // TODO: xử lý logic xoá thuê sách
        return null;
    }
}
