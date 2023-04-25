<?php

namespace App\Http\Controllers\APIs\V1;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Package\StorePackageRequest;
use App\Http\Requests\V1\Package\UpdatePackageRequest;
use App\Http\Resources\V1\Package\PackageResource;
use App\Http\Resources\V1\Package\PackageResourceCollection;
use App\Http\Responses\BaseResponse;
use App\Models\Package;
use App\Repositories\Package\PackageRepository;
use App\Repositories\User\UserRepository;
use App\Services\Package\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller {
    /**
     * Sử dụng kiểu định dạng trả về API
     */
    use BaseResponse;
    /**
     * Hàm khởi tạo 
     */
    public function __construct() {
        new PackageService(new PackageRepository(), new UserRepository());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        Log::info("***** Lấy tất cả gói ưu đãi *****");
        try {
            $data = new PackageResourceCollection(PackageService::getAllPackages($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_PACKAGE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_PACKAGE_FAILED
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request) {
        Log::info("***** Tạo mới một gói ưu đãi *****");
        try {
            $data = new PackageResource(PackageService::createPackage($request));
            return $this->success(
                $request,
                $data,
                MessageConstant::$CREATE_PACKAGE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CREATE_PACKAGE_FAILED
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, mixed $id) {
        Log::info("***** Lấy chi tiết một gói ưu đãi *****");
        try {
            $data = new PackageResource(PackageService::getAPackage($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_PACKAGE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_PACKAGE_FAILED
            );
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, mixed $id) {
        Log::info("***** Cập nhật một gói ưu đãi *****");
        try {
            $data = new PackageResource(PackageService::updatePackage($request, $id));
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPDATE_PACKAGE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPDATE_PACKAGE_FAILED
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, mixed $id) {
        Log::info("***** Xoá một gói ưu đãi *****");
        try {
            PackageService::deletePackage($id);
            return $this->success(
                $request,
                null,
                MessageConstant::$DELETE_PACKAGE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_PACKAGE_FAILED
            );
        }
    }
    /**
     * Điều hướng về đăng ký gói cho thành viên
     */
    public function register(Request $request, mixed $id) {
        Log::info("***** Đăng ký gói ưu đãi *****");
        try {
            $data = PackageService::registerPackage($request, $id);
            return $this->success(
                $request,
                $data,
                MessageConstant::$REGISTER_PACKAGE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$REGISTER_PACKAGE_FAILED
            );
        }
    }
}
