<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Seller\Resources\ProductCategoryResource;
use RedJasmine\Product\Services\Category\ProductCategoryService;

class ProductCategoryController extends Controller
{

    public function service() : ProductCategoryService
    {
        return app(ProductCategoryService::class);
    }

    public function index() : AnonymousResourceCollection
    {
        $tree = $this->service()->tree();

        return ProductCategoryResource::collection($tree);
    }

    public function show($id) : ProductCategoryResource
    {
        return new ProductCategoryResource($this->service()->find($id));
    }


}
