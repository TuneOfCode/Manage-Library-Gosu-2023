<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookUser extends Pivot {
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'amount',
        'payment',
        'discount',
        'unit',
        'extra_money',
        'status',
        'borrowed_at',
        'estimated_returned_at',
        'returned_at',
        'repayed_at',
    ];
    /**
     * Bật created_at và updated_at
     */
    public $timestamps = true;
    /**
     * Một thành viên có nhiều lịch sử thuê sách
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    /**
     * Một sách có nhiều lịch sử thuê sách
     */
    public function book() {
        return $this->belongsTo(Book::class);
    }
}
