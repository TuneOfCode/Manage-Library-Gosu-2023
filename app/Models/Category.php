<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /**
     * Dữ liệu cho phép
     */
    protected $fillable = [
        'name'
    ];
    /**
     * Bật created_at và updated_at
     */
    public $timestamps = true;
     /**
     * Một loại sách có nhiều sách
     */
    public function books() {
        return $this->hasMany(Book::class);
    }
}
