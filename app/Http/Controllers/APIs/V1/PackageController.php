<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Package\StorePackageRequest;
use App\Http\Resources\PackageResource;
use App\Http\Responses\BaseResponse;
use App\Models\Package;
use App\Repositories\Package\PackageRepository;
use Illuminate\Http\Request;


class PackageController extends Controller
{
    /**
     * định dạng kiểu trả về API
     */
    use BaseResponse;
    /**
     * [Test] thuộc tính repo
     */
    private PackageRepository $packageRepo;
    /**
     * Hàm khởi tạo
     */
    public function __construct(){
        $this->packageRepo = new PackageRepository();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $listOfPackage = $this->packageRepo->findAll(10);
        return $this->success($request, $listOfPackage,"Lấy dữ liệu ra thành công");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
        return new PackageResource(Package::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $package = $this->packageRepo->findOne($id);
        if(empty($package)){
            return $this->error($request,"Gói dịch vụ không tìm thấy");
        }
        return $this->success($request,"Chi tiết của gói dịch vụ");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        //
    }
}
