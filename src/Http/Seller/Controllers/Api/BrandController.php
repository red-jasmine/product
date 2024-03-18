<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;


use Illuminate\Http\Request;
use RedJasmine\Product\Http\Seller\Resources\BrandCollection;
use RedJasmine\Product\Http\Seller\Resources\BrandResource;
use RedJasmine\Product\Services\Brand\BrandService;

class BrandController extends Controller
{
    public function __construct(public BrandService $service)
    {
    }


    public function index(Request $request)
    {
        $result = $this->service->query()->paginate();
        return BrandResource::collection($result);
    }

    public function show($id)
    {
        $result = $this->service->query->find($id);

        return new BrandResource($result);
    }

}
