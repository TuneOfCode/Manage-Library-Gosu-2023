<?php

namespace App\Services\Book;

use App\Http\Resources\BookResourceCollection;
use App\Models\Book;
use App\Repositories\Book\BookRepository;
use App\Repositories\IBaseRepository;
use App\Services\Book\IBookService;

class BookService implements IBookService
{
    /**
     * 
     */
    private static IBaseRepository $bookRepo;
    /**
     * 
     */
    public function __construct(IBaseRepository $bookRepo) {
        self::$bookRepo = $bookRepo;
    }
    public static function getAllBook($attributes)
    {
        // return Book::all();
        // $result = null;
        // if(count($attributes)==0){
        //     $result = new BookResourceCollection(Book::paginate());
        // }else{
        //     $result = new BookResourceCollection(Book::where($attributes)->paginate());
        // }
        $result = new BookResourceCollection(self::$bookRepo->findAll($attributes, 10));
        dd($result);    
        // dd(Book::where(['name', 'like', 'ce'])->paginate(10));
        return $result;
    }

    public static function getByIdBook($id)
    {
        return Book::findOrFail($id);
    }

    public static function createBook($data)
    {
        return Book::create($data);
    }

    public static function updateBook($id, $data)
    {
        $book = Book::findOrFail($id);
        $book->update($data);
        return $book;
    }

    public static function deleteBook($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
    }
}