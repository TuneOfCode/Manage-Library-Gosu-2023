<?php

namespace App\Services\BookUser;

use App\Constants\GlobalConstant;
use App\Constants\MessageConstant;
use App\Enums\BookLabel;
use App\Enums\PackageType;
use App\Enums\RentBookStatus;
use App\Http\Filters\V1\BookUser\BookUserFilter;
use App\Http\Responses\BaseHTTPResponse;
use App\Repositories\Book\IBookRepository;
use App\Repositories\BookUser\IBookUserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookUserService implements IBookUserService {
    /**
     * Đối tượng repository
     */
    private static IBookUserRepository $bookUserRepo;
    private static IBookRepository $bookRepo;
    /**
     * Đối tượng lọc dữ liệu
     */
    private static BookUserFilter $filter;
    /**
     * Hàm khởi tạo
     */
    public function __construct(
        IBookUserRepository $bookUserRepo,
        IBookRepository $bookRepo
    ) {
        self::$bookUserRepo = $bookUserRepo;
        self::$bookRepo = $bookRepo;
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
     * Xử lý đầu vào chuỗi sang mảng
     */
    private static function convertStringToArray(string $str) {
        $array = explode(',', $str);
        foreach ($array as $key => $value) {
            $array[$key] = trim($value);
        }
        return $array;
    }
    /**
     * Chính sách giảm giá cho từng gói
     */
    private static function getDiscountByPackageType(string $packageType) {
        $brozen = strtolower(PackageType::BROZEN()->value);
        $silver = strtolower(PackageType::SILVER()->value);
        $gold = strtolower(PackageType::GOLD()->value);
        switch ($packageType) {
            case $brozen:
                return 5; // giảm 5%
            case $silver:
                return 10; // giảm 10%
            case $gold:
                return 15; // giảm 15%
            default:
                return 0;
        }
    }
    /**
     * Dịch vụ cho mượn sách
     */
    public static function borrowBooks(Request $request) {
        //* DONE: xử lý logic cho mượn sách
        // - dữ liệu đầu vào của mượn sách bao gồm:
        //  + userId: id của thành viên hiện tại Auth::user()->id
        //  + bookIds: id của nhiều sách
        //  + estimatedReturnedAt: ngày dự kiến trả sách (hệ thống gợi ý)
        $bookIds = self::convertStringToArray($request->get('bookIds'));

        // lấy ra số lượng sách mà thành viên muốn mượn
        $statisticsBooks = [];
        foreach ($bookIds as $bookId) {
            if (isset($statisticsBooks[$bookId])) {
                $statisticsBooks[$bookId] = [
                    'id' => $bookId,
                    'amount' => $statisticsBooks[$bookId]['amount'] + 1
                ];
            } else {
                $statisticsBooks[$bookId] = [
                    'id' => $bookId,
                    'amount' => 1
                ];
            }
        }

        $bookIds = array_unique($bookIds, SORT_NUMERIC);

        // xử lý đầu vào ngày dự kiến trả sách
        $date = $request->get('estimatedReturnedAt')
            ? Carbon::createFromFormat(
                'd/m/Y',
                $request->get('estimatedReturnedAt')
            )
            : now()->addWeek();
        $estimatedReturnDate = $date->format(GlobalConstant::$FORMAT_DATETIME_DB);

        // ngày dự kiến trả sách phải lớn hơn ngày hiện tại
        if ($estimatedReturnDate < now()) {
            throw new \Exception(
                MessageConstant::$ESTIMATED_RETURN_DATE_INVALID,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }
        $currentMember = Auth::user();

        // kiểm tra điểm uy tín của thành viên (< 50 thì không cho mượn)
        if ($currentMember->score < 50) {
            throw new \Exception(
                MessageConstant::$MEMBER_NOT_ENOUGH_SCORE,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        $packageType = $currentMember->package()->first()->type;
        $isBookVip = false;
        $isNormalPackage = $packageType === strtolower(PackageType::NORMAL()->value);
        // lấy ra thông tin chi tiết và kiếm tra từng sách mà thành viên muốn mượn
        foreach ($bookIds as $bookId) {
            $book = self::$bookRepo->findById($bookId);
            if (empty($book) || !$book->status) {
                throw new \Exception(
                    MessageConstant::$BOOK_NOT_EXIST,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            // kiểm tra số lượng tồn kho của sách mà thành viên đang muốn mượn
            $amount = $statisticsBooks[$bookId]['amount'];
            if ($book->quantity < $amount) {
                throw new \Exception(
                    MessageConstant::$BOOK_NOT_ENOUGH_QUANTITY,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            // kiểm tra sách có phải là sách vip hay không
            if ($book->label === strtolower(BookLabel::VIP()->value)) {
                $isBookVip = true;
            }
        }

        // kiểm tra gói dịch vụ hiện tại mà thành viên đang đăng ký
        if ($isBookVip && $isNormalPackage) {
            throw new \Exception(
                MessageConstant::$MEMBER_NOT_VIP,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // kiểm tra số lượng sách mà thành viên đang mượn (không vượt quá 5 cuốn)
        $countBorrowingBooks = self::$bookUserRepo->countBorrowedBooks(
            $currentMember->id
        );
        if ($countBorrowingBooks >= 5) {
            throw new \Exception(
                MessageConstant::$MEMBER_BORROWING_BOOKS_LIMIT,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // thêm vào lịch sử cho thuê sách ở trạng thái đang chờ duyệt
        $totalMoney = 0;
        $intoMoney = 0;
        foreach ($bookIds as $bookId) {
            $book = self::$bookRepo->findById($bookId);
            $amount = $statisticsBooks[$bookId]['amount'];
            $payment = $amount * $book->loan_price;
            $discount = self::getDiscountByPackageType(
                $packageType
            );
            $data = [
                'amount' => $amount,
                'payment' => $payment,
                'discount' => $discount,
                'estimated_returned_at' => $estimatedReturnDate
            ];
            $currentMember->books()->attach($bookId, $data);
            $totalMoney += $payment;
            $intoMoney += $payment - ($payment * $discount / 100);
        }

        // trả lại kết quả
        $result = self::$bookUserRepo->findAll(
            [
                'user_id' => $currentMember->id,
            ],
            ['user', 'book']
        );
        return $result;
    }
    /**
     * Dịch vụ phê duyệt cho mượn sách
     */
    public static function approveBorrowingBooks(Request $request) {
        //* DONE: xử lý logic phê duyệt cho mượn sách
        // - dữ liệu đầu vào của phê duyệt cho mượn sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ duyệt
        foreach ($ids as $id) {
            $bookUser = self::$bookUserRepo->findById($id);
            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            $pendingStatus = strtolower(RentBookStatus::PENDING()->value);
            if ($bookUser->status !== $pendingStatus) {
                throw new \Exception(
                    MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
        }

        // thay đổi trạng thái của các bản ghi thuê sách thành đã duyệt và đang chờ thanh toán
        $result = [];
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::PAYING()->value);
            self::$bookUserRepo->update([
                'status' => $newStatus,
                'approved_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB)
            ], $id);
            $result[] = self::$bookUserRepo->findById($id);
        }

        // trả lại kết quả
        return $result;
    }
    /**
     * Dịch vụ từ chối yêu cầu mượn sách
     */
    public static function rejectBorrowingBooks(Request $request) {
        //* DONE: xử lý logic từ chối yêu cầu mượn sách
        // - điều kiện để từ chối yêu cầu mượn sách:
        //  + nếu trong kho sách không còn đủ sách để cho mượn
        //  + nếu thành viên không trả sách ở lần mượn trước đó
        //  + nếu thành viên trả sách quá hạn quá nhiều lần (>= 3 lần)
        // - dữ liệu đầu vào của phê duyệt cho mượn sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ duyệt
        foreach ($ids as $id) {
            $bookUser = self::$bookUserRepo->findById($id);
            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            $pendingStatus = strtolower(RentBookStatus::PENDING()->value);
            if ($bookUser->status !== $pendingStatus) {
                throw new \Exception(
                    MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
        }

        // thay đổi trạng thái của các bản ghi thuê sách thành đã từ chối
        $result = [];
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::REJECTED()->value);
            self::$bookUserRepo->update([
                'status' => $newStatus,
                'rejected_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB)
            ], $id);
            $result[] = self::$bookUserRepo->findById($id);
        }
        // trả lại kết quả (có thể xoá các bản ghi thuê sách này)
        return $result;
    }
    /**
     * Dịch vụ trả tiền thuê sách
     */
    public static function payBorrowingBooks(Request $request) {
        // *DONE: xử lý logic trả tiền thuê sách
        // - dữ liệu đầu vào của trả tiền thuê sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ thanh toán
        $currentMember = Auth::user();
        $intoMoney = 0;
        foreach ($ids as $id) {
            $payingStatus = strtolower(RentBookStatus::PAYING()->value);
            $bookUser = self::$bookUserRepo->findOne([
                'id' => $id,
                'user_id' => $currentMember->id, // chỉ cho phép thanh toán của thành viên hiện tại
                'status' => $payingStatus
            ]);

            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST
                        . " or " .
                        MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            $bookUser = $bookUser->getAttributes();
            $intoMoney += $bookUser['payment']
                - ($bookUser['payment'] * $bookUser['discount'] / 100);
        }
        // kiểm tra số dư tài khoản của thành viên
        if ($currentMember->balance < $intoMoney) {
            throw new \Exception(
                MessageConstant::$MEMBER_NOT_ENOUGH_BALANCE,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // tiến hành tính, trừ tiền và cập nhật của thành viên
        $currentMember->balance -= $intoMoney;
        $currentMember->save();

        // thay đổi trạng thái của các bản ghi thuê sách thành đã thanh toán
        $result = [];
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::RECEIVING()->value);
            $bookUser = self::$bookUserRepo->findById($id);
            $book = self::$bookRepo->findById($bookUser['book_id']);
            $positision = $book['position'];
            self::$bookUserRepo->update([
                'status' => $newStatus,
                'paid_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB),
                'note' => "Please go to $positision to receive books"
            ], $id);
            $result[] = self::$bookUserRepo->findById($id);
        }
        // trả lại kết quả 
        return $result;
    }
    /**
     * Dịch vụ xác nhận nhận sách
     */
    public static function confirmReceivingBooks(Request $request) {
        // *DONE: xử lý logic xác nhận nhận sách
        // - dữ liệu đầu vào của xác nhận nhận sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ nhận sách
        foreach ($ids as $id) {
            $bookUser = self::$bookUserRepo->findById($id);
            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            $pendingStatus = strtolower(RentBookStatus::RECEIVING()->value);
            if ($bookUser->status !== $pendingStatus) {
                throw new \Exception(
                    MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
        }
        // thay đổi trạng thái của các bản ghi thuê sách thành đang mượn
        $result = [];
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::BORROWING()->value);
            self::$bookUserRepo->update([
                'status' => $newStatus,
                'borrowed_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB)
            ], $id);
            $bookUser = self::$bookUserRepo->findById($id);
            $result[] =  $bookUser;

            // trừ đi số lượng tồn kho của sách
            $book = self::$bookRepo->findById($bookUser['book_id']);
            $newQuantity = $book['quantity'] - $bookUser['amount'];
            self::$bookRepo->update([
                'quantity' => $newQuantity
            ], $bookUser['book_id']);
        }
        // bật thực hiện công việc thông báo trước 1-2 ngày trước khi hết hạn trả sách qua hình thức gửi email
        // trả lại kết quả 
        return $result;
    }
    /**
     * Dich vụ hủy yêu cầu mượn sách
     */
    public static function cancelBorrowingBooks(Request $request) {
        // * DONE: xử lý logic hủy yêu cầu mượn sách
        // - dữ liệu đầu vào của hủy yêu cầu mượn sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang chờ duyệt
        $currentMember = Auth::user();
        foreach ($ids as $id) {
            $pendingStatus = strtolower(RentBookStatus::PENDING()->value);
            $bookUser = self::$bookUserRepo->findOne([
                'id' => $id,
                'user_id' => $currentMember->id,
                'status' => $pendingStatus
            ]);
            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST
                        . " or " .
                        MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
        }
        // thay đổi trạng thái của các bản ghi thuê sách thành đã hủy
        $result = [];
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::CANCEL()->value);
            self::$bookUserRepo->update([
                'status' => $newStatus,
                'canceled_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB)
            ], $id);
            $result[] = self::$bookUserRepo->findById($id);
        }
        // trả lại kết quả (có thể xoá các bản ghi thuê sách này)
        return $result;
    }
    /**
     * Dịch vụ trả sách
     */
    public static function returnBooks(Request $request) {
        // * DONE: xử lý logic trả sách
        // - dữ liệu đầu vào của trả sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là đang mượn
        foreach ($ids as $id) {
            $bookUser = self::$bookUserRepo->findById($id);
            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            $pendingStatus = strtolower(RentBookStatus::BORROWING()->value);
            if ($bookUser->status !== $pendingStatus) {
                throw new \Exception(
                    MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
        }
        // thay đổi trạng thái của các bản ghi thuê sách thành đã trả
        $result = [];
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::RETURNED()->value);
            self::$bookUserRepo->update([
                'status' => $newStatus,
                'returned_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB)
            ], $id);
            $result[] = self::$bookUserRepo->findById($id);
        }
        // trả lại kết quả
        return $result;
    }
    /**
     * Dịch vụ kiểm tra sách trả có quá hạn không
     */
    public static function checkOverdueBooks() {
        // *DONE: xử lý logic kiểm tra sách trả có quá hạn không
        // lấy ra các bản ghi thuê sách có trạng thái là đang mượn
        $borrowingBooks = self::$bookUserRepo->findAll([
            'status' => strtolower(RentBookStatus::BORROWING()->value)
        ]);

        // kiểm tra thời gian trả sách có quá hạn không
        foreach ($borrowingBooks as $borrowingBook) {
            if ($borrowingBook['estimated_returned_at'] < now()) {
                // nếu trễ hạn mỗi ngày thì phụ phí tăng thêm 5.000 vnđ
                $dueDate = Carbon::createFromFormat(
                    GlobalConstant::$FORMAT_DATETIME_DB,
                    $borrowingBook['estimated_returned_at']
                )->diffInDays(now());
                $extraMoney = $dueDate * 5000;

                // tự động cập nhật trạng thái của các bản ghi thuê sách thành đã trả quá hạn
                $newStatus = str_replace(
                    '_',
                    ' ',
                    strtolower(RentBookStatus::OVERDUE_RETURNED()->value)
                );
                self::$bookUserRepo->update([
                    'status' => $newStatus,
                    'extra_money' => $extraMoney,
                ], $borrowingBook['id']);
            }
        }
    }
    /**
     * Dịch vụ tăng tiền phụ phí
     */
    public static function increaseExtraMoney() {
        // *DONE: xử lý logic tăng tiền phụ phí
        // lấy ra các bản ghi thuê sách có trạng thái là sách trả quá hạn
        $overdueReturnedBooks = self::$bookUserRepo->findAll([
            'status' =>
            str_replace(
                '_',
                ' ',
                strtolower(RentBookStatus::OVERDUE_RETURNED()->value)
            )
        ]);
        foreach ($overdueReturnedBooks as $overdueReturnedBook) {
            if ($overdueReturnedBook['estimated_returned_at'] < now()) {
                // nếu trễ hạn mỗi ngày thì phụ phí tăng thêm 5.000 vnđ
                $dueDate = Carbon::createFromFormat(
                    GlobalConstant::$FORMAT_DATETIME_DB,
                    $overdueReturnedBook['estimated_returned_at']
                )->diffInDays(now());
                $extraMoney = $dueDate * 5000;

                // tự động cập nhật tiền phụ phí
                self::$bookUserRepo->update([
                    'extra_money' => $extraMoney,
                ], $overdueReturnedBook['id']);
            }
        }
    }
    /**
     * Dịch vụ kiểm tra không trả sách
     */
    public static function checkNotReturnBooks() {
        // *DONE: xử lý logic kiểm tra không trả sách
        // lấy ra các bản ghi thuê sách có trạng thái là đang mượn
        $borrowingBooks = self::$bookUserRepo->findAll([
            'status' => strtolower(RentBookStatus::BORROWING()->value)
        ]);

        // kiểm tra thời gian trả sách có quá hạn sau 2 tuần kể từ thời hạn dự kiến trả sách
        foreach ($borrowingBooks as $borrowingBook) {
            $estimatedReturnDate = Carbon::createFromFormat(
                GlobalConstant::$FORMAT_DATETIME_DB,
                $borrowingBook['estimated_returned_at']
            );
            $estimatedReturnDate->addWeeks(2);
            if ($estimatedReturnDate < now()) {
                // tự động cập nhật trạng thái của các bản ghi thuê sách thành không trả sách
                $newStatus =
                    str_replace(
                        '_',
                        ' ',
                        strtolower(RentBookStatus::NOT_RETURNED()->value)
                    );
                self::$bookUserRepo->update([
                    'status' => $newStatus
                ], $borrowingBook['id']);
            }
        }
    }
    /**
     * Dịch vụ trả tiền phụ phí (trễ hạn hoặc không trả sách)
     */
    public static function payExtraMoney(Request $request) {
        // !!check: xử lý logic trả tiền phụ phí (trễ hạn hoặc không trả sách)
        // - dữ liệu đầu vào của trả tiền phụ phí (trễ hạn hoặc không trả sách) bao gồm:
        //  + ids của các bản ghi thuê sách
        $currentMember = Auth::user();
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách của thành viên hiện tại phải là:
        //  + đã trả quá hạn
        //  + không trả sách
        $totalExtraMoney = 0;
        $result = [];
        foreach ($ids as $id) {
            $histories = self::$bookUserRepo->getAllHistoryWithOverdueAndNotReturn(
                $id,
                $currentMember->id,
            )->toArray();
            if (empty($histories)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST
                        . " or " .
                        MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
            $totalExtraMoney = 0;
            foreach ($histories as $history) {
                $totalExtraMoney += $history["extra_money"];
            }

            // kiểm tra số dư tài khoản của thành viên
            if ($currentMember->balance < $totalExtraMoney) {
                throw new \Exception(
                    MessageConstant::$MEMBER_NOT_ENOUGH_BALANCE,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            // thêm thời gian trả tiền phụ phí
            self::$bookUserRepo->update([
                'extra_money_at' => now()->format(GlobalConstant::$FORMAT_DATETIME_DB)
            ], $id);

            $result[] = self::$bookUserRepo->findById($id);
        }

        // tiến hành tính, trừ tiền và cập nhật của thành viên
        $currentMember->balance -= $totalExtraMoney;
        $currentMember->save();

        // trả lại kết quả
        return $result;
    }
    /**
     * Dịch vụ xoá thuê sách
     */
    public static function deleteBookUser(Request $request) {
        // * DONE: xử lý logic xoá thuê sách
        // - dữ liệu đầu vào của xoá thuê sách bao gồm:
        //  + ids của các bản ghi thuê sách
        $ids = array_unique(
            self::convertStringToArray($request->get('ids')),
            SORT_NUMERIC
        );

        // kiểm tra trạng thái hiện tại của các bản ghi thuê sách phải là:
        //  + bị từ chối
        //  + bị huỷ
        foreach ($ids as $id) {
            $bookUser = self::$bookUserRepo->findById($id);
            if (empty($bookUser)) {
                throw new \Exception(
                    MessageConstant::$BOOK_USER_NOT_EXIST,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }

            $cancelStatus = strtolower(RentBookStatus::CANCEL()->value);
            $rejectStatus = strtolower(RentBookStatus::REJECTED()->value);
            if (
                $bookUser->status !== $cancelStatus
                && $bookUser->status !== $rejectStatus
            ) {
                throw new \Exception(
                    MessageConstant::$INVALD_STATUS,
                    BaseHTTPResponse::$BAD_REQUEST
                );
            }
        }

        // xoá các bản ghi thuê sách
        foreach ($ids as $id) {
            $newStatus = strtolower(RentBookStatus::RETURNED()->value);
            self::$bookUserRepo->destroy($id);
        }

        // trả lại kết quả
        return null;
    }
}
