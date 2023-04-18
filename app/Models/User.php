<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'username',
        'password',
        'avatar',
        'phone',
        'address',
        'score',
        'status',
        'balance',
        'package_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'refresh_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Bật created_at và updated_at
     */
    public $timestamps = true;
    /**
     * 
     * Một thành viên chỉ đăng ký đúng một gói
     */
    public function package() {
        return $this->belongsTo(Package::class);
    }
    /**
     *  Định nghĩa trường dữ liệu đầu vào cho xác thực
     */
    public function findForPassport($username) {
        return self::where('username', $username)->first(); // change column name whatever you use in credentials
    }
}
