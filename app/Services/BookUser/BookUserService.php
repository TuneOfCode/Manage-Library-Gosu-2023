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
        //* DOING: xử lý logic cho mượn sách
        // - dữ liệu đầu vào của mượn sách bao gồm:
        //  + userId: id của thành viên hiện tại Auth::user()->id
        //  + bookIds: id của nhiều sách
        //  + estimatedReturnDate: ngày dự kiến trả sách (hệ thống gợi ý)
        // kiểm tra điểm uy tín của thành viên (< 50 thì không cho mượn)
        // kiểm tra gói dịch vụ hiện tại mà thành viên đang đăng ký
        // kiểm tra số lượng tồn kho của sách mà thành viên đang muốn mượn
        // kiểm tra số lượng sách mà thành viên đang mượn (không vượt quá 5 cuốn)
        // thêm vào lịch sử cho thuê sách ở trạng thái đang chờ duyệt
        // trả lại kết quả
        return [];
    }
    /**
     * Dịch vụ phê duyệt cho mượn sách
     */
    public static function approveBorrowingBooks(Request $request) {
        // TODO: xử lý logic phê duyệt cho mượn sách
        // - dữ liệu đầu vào của phê duyệt cho mượn sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ duyệt
        // thay đổi trạng thái của các bản ghi thuê sách thành đang chờ nhận sách
        // trả lại kết quả
        return [];
    }
    /**
     * Dịch vụ từ chối yêu cầu mượn sách
     */
    public static function rejectBorrowingBooks(Request $request) {
        // TODO: xử lý logic từ chối yêu cầu mượn sách
        // - điều kiện để từ chối yêu cầu mượn sách:
        //  + nếu trong kho sách không còn đủ sách để cho mượn
        //  + nếu thành viên không trả sách ở lần mượn trước đó
        //  + nếu thành viên trả sách quá hạn quá nhiều lần (>= 3 lần)
        // - dữ liệu đầu vào của phê duyệt cho mượn sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ duyệt
        // thay đổi trạng thái của các bản ghi thuê sách thành đã từ chối
        // trả lại kết quả (có thể xoá các bản ghi thuê sách này)
        return [];
    }
    /**
     * Dịch vụ trả tiền thuê sách
     */
    public static function payBorrowingBooks(Request $request) {
        // TODO: xử lý logic trả tiền thuê sách
        // - dữ liệu đầu vào của trả tiền thuê sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ thanh toán
        // kiểm tra số dư tài khoản của thành viên
        // tiến hành tính và trừ tiền của thành viên
        // thay đổi trạng thái của các bản ghi thuê sách thành đã thanh toán
        // trả lại kết quả
        return [];
    }
    /**
     * Dịch vụ xác nhận nhận sách
     */
    public static function confirmReceivingBooks(Request $request) {
        // TODO: xử lý logic xác nhận nhận sách
        // - dữ liệu đầu vào của xác nhận nhận sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ nhận sách
        // thay đổi trạng thái của các bản ghi thuê sách thành đang mượn
        // bật thực hiện công việc thông báo trước 1-2 ngày trước khi hết hạn trả sách qua hình thức gửi email
        // trả lại kết quả
        return [];
    }
    /**
     * Dich vụ hủy yêu cầu mượn sách
     */
    public static function cancelBorrowingBooks(Request $request) {
        // TODO: xử lý logic hủy yêu cầu mượn sách
        // - dữ liệu đầu vào của hủy yêu cầu mượn sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ duyệt
        // thay đổi trạng thái của các bản ghi thuê sách thành đã hủy
        // trả lại kết quả (có thể xoá các bản ghi thuê sách này)
        return [];
    }
    /**
     * Dịch vụ trả sách
     */
    public static function returnBooks(Request $request) {
        // TODO: xử lý logic trả sách
        // - dữ liệu đầu vào của trả sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang mượn
        // thay đổi trạng thái của các bản ghi thuê sách thành đã trả
        // trả lại kết quả
        return [];
    }
    /**
     * Dịch vụ kiểm tra sách trả có quá hạn không
     */
    public static function checkOverdueBooks(Request $request) {
        // TODO: xử lý logic kiểm tra sách trả có quá hạn không
        // - dữ liệu đầu vào của kiểm tra sách trả có quá hạn không bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang mượn
        // kiểm tra thời gian trả sách có quá hạn không
        // tự động cập nhật trạng thái của các bản ghi thuê sách thành đã trả quá hạn
        // trả lại kết quả
    }
    /**
     * Dịch vụ kiểm tra không trả sách
     */
    public static function checkNotReturnBooks(Request $request) {
        // TODO: xử lý logic kiểm tra không trả sách
        // - dữ liệu đầu vào của kiểm tra không trả sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang mượn
        // kiểm tra thời gian trả sách có quá hạn không
        // tự động cập nhật trạng thái của các bản ghi thuê sách thành không trả sách
        //  sau 1-2 tuần kể từ ngày hết hạn trả sách
        // trả lại kết quả
    }
    /**
     * Dịch vụ xoá thuê sách
     */
    public static function deleteBookUser(Request $request) {
        // TODO: xử lý logic xoá thuê sách
        // - dữ liệu đầu vào của xoá thuê sách bao gồm:
        //  + ids của các bản ghi thuê sách
        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là:
        //  + bị từ chối
        //  + bị huỷ
        // xoá các bản ghi thuê sách
        // trả lại kết quả
        return null;
    }
}
