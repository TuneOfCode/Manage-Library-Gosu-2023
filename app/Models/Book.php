<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'loan_price',
        'status',
        'author',
        'published_at',
        'category_id'
    ];
    /**
     * Bật created_at và updated_at
     */
    public $timestamps = true;
    /**
     * Mộ sách chỉ có 1 loại
     *
     */
    public function category() {
        return $this->belongsTo(Category::class);
        }
    public function scopeFilterBooks(){
        
    }
}
