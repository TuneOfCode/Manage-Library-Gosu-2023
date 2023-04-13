<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResponse;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * [Test] Thuộc tính repo
     */
    private UserRepository $userRepo;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        $this->userRepo = new UserRepository();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $listOfUsers = $this->userRepo->findAll(10);
        return $this->success($request, $listOfUsers, "Lấy ra tất cả người dùng thành công!");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = $this->userRepo->findOne($id);
        if (empty($user)) {
            return $this->error($request, "Lỗi không tìm thấy người dùng");
        }
        return $this->success($request, $user, "Lấy chi tiết của một người dùng thành công!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
