<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Seller\Resources\ProductSellerCategoryResource;
use RedJasmine\Product\Services\Category\ProductSellerCategoryService;

class ProductSellerCategoryController extends Controller
{

    public function service() : ProductSellerCategoryService
    {
        $service = app(ProductSellerCategoryService::class);
        $service->setOperator($this->getUser());
        $service->setOwner($this->getOwner());
        return $service;
    }

    public function index() : AnonymousResourceCollection
    {
        return ProductSellerCategoryResource::collection($this->service()->tree());
    }

    /**
     * @param Request $request
     *
     * @return ProductSellerCategoryResource
     * @throws Exception
     */
    public function store(Request $request) : ProductSellerCategoryResource
    {
        $result = $this->service()->create($request->all());
        return new  ProductSellerCategoryResource($result);
    }

    /**
     * @param $id
     *
     * @return ProductSellerCategoryResource
     */
    public function show($id) : ProductSellerCategoryResource
    {
        $result = $this->service()->find($id);
        return new  ProductSellerCategoryResource($result);
    }

    public function update(Request $request, $id) : ProductSellerCategoryResource
    {

        if ($request->isMethod('PATCH')) {
            $result = $this->service()->modify($id, $request->all());
        } else {
            $result = $this->service()->update($id, $request->all());
        }

        return new  ProductSellerCategoryResource($result);
    }

    public function destroy($id)
    {
    }
}
