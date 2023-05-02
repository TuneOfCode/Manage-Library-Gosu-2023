<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    use HasFactory;
    /**
     * Những thuộc tính cho phép có
     */
    protected $fillable = [
        'category_id',
        'name',
        'image',
        'description',
        'position',
        'quantity',
        'price',
        'loan_price',
        'author',
        'published_at',
        'status',
    ];
    /**
     * Bật created_at và updated_at
     */
    public $timestamps = true;
    /**
     * Một cuốn sách thuộc một loại sách
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }
    /**
     * Nhiều cuốn sách có thể được mượn bởi nhiều thành viên
     */
    public function users() {
        return $this->belongsToMany(User::class, 'book_user', 'book_id', 'user_id');
    }
}
