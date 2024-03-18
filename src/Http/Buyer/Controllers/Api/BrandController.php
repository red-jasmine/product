<?php

namespace RedJasmine\Product\Http\Buyer\Controllers\Api;

use Illuminate\Http\Request;
use RedJasmine\Product\Http\Buyer\Resources\BrandResource;
use RedJasmine\Product\Services\Brand\BrandService;

class BrandController extends Controller
{

    public function __construct(protected BrandService $service)
    {
    }


    public function index(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = $this->service->query()->paginate();
        return BrandResource::collection($result);
    }

    public function show($id) : BrandResource
    {
        $result = $this->service->query()->find($id);

        return new BrandResource($result);
    }

}
