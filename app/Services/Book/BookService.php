<?php

namespace App\Services\Book;

use App\Constants\GlobalConstant;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookResourceCollection;
use App\Models\Book;
use App\Repositories\Book\IBookRepository;
use App\Repositories\IBaseRepository;
use App\Services\Book\IBookService;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BookService implements IBookService
{
    /**
     * 
     */
    private static IBookRepository $bookRepo;
    /**
     * 
     */
    public function __construct(IBookRepository $bookRepo) {
        self::$bookRepo = $bookRepo;
    }
    public static function getAllBook($attributes)
    {
        $result = self::$bookRepo->findAll($attributes, 10);
        return $result;
    }

    public static function getByIdBook($id)
    {
        $result = self::$bookRepo->findById($id);
        $status = $result->status;
        // !$status && Auth::user()->hasRole('member')
        // trạng thái sách bị khóa và vai trò là người dùng 
        // thì không thể truy cập được
        if (!$status ) {
            throw new \Exception("Sách không tìm thấy", 404);       
        }
        return $result;
    }

    public static function createBook($data)
    {
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

    public static function updateBook($data)
    {

        dd($data->toArray());
        $updateData = $data->toArray();
        $method = $data->getMethod();
        if ($method === 'PATCH') {

        }
        $id = 0;// $updateData['id'];
        dd($data);
        $isUpdated = self::$bookRepo->update($updateData, $id);
        if (!$isUpdated) {
            throw new \Exception("Cập nhật thất bại", 400);                  
        }
        $book = self::$bookRepo->findById($id);
        return $book;
    }

    public static function deleteBook($id)
    {
        self::$bookRepo->destroy($id);
        return true;
    }
    /**
     * 
     */
    public static function newBooks()
    {
        return self::$bookRepo->getTypeBook();
    }
    /**
     * sách cũ
     */
    public static function oldBooks()
    {
        return self::$bookRepo->getTypeBook('asc');
    }
}