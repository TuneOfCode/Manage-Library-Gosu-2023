<?php

namespace App\Services\Book;

use App\Constants\GlobalConstant;
use App\Constants\MessageConstant;
use App\Constants\RoleConstant;
use App\Http\Filters\V1\Book\BookFilter;
use App\Http\Responses\BaseHTTPResponse;
use App\Repositories\Book\IBookRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookService implements IBookService {
    /**
     * Đối tượng repository
     */
    private static IBookRepository $bookRepo;
    /**
     * Đối tượng lọc dữ liệu
     */
    private static BookFilter $filter;
    /**
     * Hàm khởi tạo
     */
    public function __construct(IBookRepository $bookRepo) {
        self::$bookRepo = $bookRepo;
        self::$filter = new BookFilter();
    }
    /**
     * Dịch vụ lấy tất cả sách
     */
    public static function getAllBooks(Request $request) {
        // xử lý request khi có query
        $query = self::$filter->transform($request);

        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra những cuốn sách theo yêu cầu
        // xử lý nếu có sắp xếp
        $column = $request->column ?? 'created_at';
        $sortType = $request->sortType ?? 'asc';
        $limit = $request->limit ?? 10;

        // lấy ra danh sách những cuốn sách với admin
        if (!empty(Auth::user()) && Auth::user()->hasRole(RoleConstant::$ADMIN)) {
            $result = self::$bookRepo->findAll(
                $query,
                $relations,
                $column,
                $sortType,
                $limit
            );
            return $result;
        }

        // lấy ra danh sách những cuốn sách được cho phép
        // hiển thị với thành viên
        $query = array_merge($query, ['status' => 1]);
        $result = self::$bookRepo->findAll(
            $query,
            $relations,
            $column,
            $sortType,
            $limit
        );
        return $result;
    }
    /**
     * Dịch vụ lấy chi tiết sách
     */
    public static function getABook(Request $request, mixed $id) {
        // xử lý request khi có mối quan hệ 
        $relations = self::$filter->getRelations($request);

        // lấy ra chi tiết cuốn sách
        $result = self::$bookRepo->findById($id, $relations);
        if (
            empty($result)
            || (!$result['status'] && !Auth::check()) // khách
            || (!$result['status']
                && Auth::check() && Auth::user()->hasRole(RoleConstant::$MEMBER)) // thành viên
        ) {
            throw new \Exception(
                MessageConstant::$BOOK_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        return $result;
    }
    /**
     * Dịch vụ tạo mới sách
     */
    public static function createBook(Request $request) {
        $data = $request->all();
        // Kiểm tra tển sách và tác giả có trùng trong CSDL hay không
        // điều kiện để trùng sách là có cùng tên và tác giả của sách đó
        $oldBook = self::$bookRepo->findOne([
            'name' => $data['name'],
            'author' => $data['author'],
            'category_id' => $data['category_id'],
        ]);
        $newBook = [];
        $bookId = 0;
        // không trùng
        if (empty($oldBook)) {
            // xử lý ngày tháng năm
            $date = Carbon::createFromFormat('d/m/Y', $data['publishedAt']);
            $data['published_at'] = $date->format(GlobalConstant::$FORMAT_DATETIME_DB);
            // xử lý ảnh upload
            $image = $data['image'];
            // Đặt lại tên file
            $currentDatetime = now()->format('YmdHis');
            $fileName = $image->getClientOriginalName();
            $imageName = "$currentDatetime-$fileName";

            // Lưu file vào thư mục
            $path = $image->storeAs('public/books', $imageName);

            // lấy đường liên kết
            $linkImage = Storage::url($path);
            // tạo mới sách
            $data['image'] = $linkImage;
            $newBook = self::$bookRepo->create($data);
            $bookId = $newBook->id;
        } else { // trùng
            // sửa lại số lượng
            $newQuantity = $oldBook->quantity + $data['quantity'];
            self::$bookRepo->update(['quantity' => $newQuantity], $oldBook->id);
            $bookId = $oldBook->id;
        }
        $newBook = self::$bookRepo->findById($bookId);
        return $newBook;
    }
    /**
     * Dịch vụ cập nhật sách
     */
    public static function updateBook(Request $request, mixed $id) {
        $data = $request->all();

        // Kiểm tra sách có tồn tại hay không
        $book = self::$bookRepo->findById($id);
        if (empty($book)) {
            throw new \Exception(
                MessageConstant::$BOOK_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // xử lý ngày tháng năm
        $data['status'] = $data['status'] == 'true' || $data['status'] == '1' ? 1 : 0;
        $date = Carbon::createFromFormat('d/m/Y', $data['publishedAt']);
        $data['published_at'] = $date->format(GlobalConstant::$FORMAT_DATETIME_DB);

        // xử lý ảnh upload
        if (!empty($data['image'])) {
            $image = $data['image'];
            // Đặt lại tên file
            $currentDatetime = now()->format('YmdHis');
            $fileName = $image->getClientOriginalName();
            $imageName = "$currentDatetime-$fileName";

            // Lưu file vào thư mục
            $path = $image->storeAs('public/books', $imageName);

            // lấy đường liên kết
            $linkImage = Storage::url($path);
            $data['image'] = $linkImage;
        }

        // tạo mới sách
        unset($data['loanPrice'], $data['publishedAt'], $data['categoryId']);
        self::$bookRepo->update($data, $id);
        $result = self::$bookRepo->findById($id);
        return $result;
    }
    /**
     * Dịch vụ xóa sách
     */
    public static function deleteBook(mixed $id) {
        $book = self::$bookRepo->findById($id);
        if (empty($book)) {
            throw new \Exception(
                MessageConstant::$BOOK_NOT_EXIST,
                BaseHTTPResponse::$BAD_REQUEST
            );
        }

        // xoá sách
        self::$bookRepo->destroy($id);
        return null;
    }
}
