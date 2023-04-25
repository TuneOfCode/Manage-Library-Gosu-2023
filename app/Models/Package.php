<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model {
    use HasFactory;
    /**
     * Những thuộc tính cho phép có
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'isActive'
    ];
    /**
     * Bật created_at và updated_at
     */
    public $timestamps = true;
    /**
     * Một gói có nhiều thành viên sử dụng
     */
    public function users() {
        return $this->hasMany(User::class);
    }
}
