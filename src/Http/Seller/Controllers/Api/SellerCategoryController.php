<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Seller\Resources\SellerCategoryResource;
use RedJasmine\Product\Services\Category\ProductSellerCategoryService;

class SellerCategoryController extends Controller
{

    public function __construct(protected ProductSellerCategoryService $service)
    {
        $this->service->setOwner($this->getOwner())->setOperator($this->getUser());
    }


    public function index() : AnonymousResourceCollection
    {
        return SellerCategoryResource::collection($this->service->tree());
    }


    /**
     * @param Request $request
     *
     * @return SellerCategoryResource
     * @throws Exception
     */
    public function store(Request $request) : SellerCategoryResource
    {
        $result = $this->service->create($request->all());
        return new  SellerCategoryResource($result);
    }

    /**
     * @param $id
     *
     * @return SellerCategoryResource
     */
    public function show($id) : SellerCategoryResource
    {
        $result = $this->service->find($id);
        return new  SellerCategoryResource($result);
    }

    public function update(Request $request, $id) : SellerCategoryResource
    {

        if ($request->isMethod('PATCH')) {
            $result = $this->service->modify($id, $request->all());
        } else {
            $result = $this->service->update($id, $request->all());
        }

        return new  SellerCategoryResource($result);
    }

    /**
     * 删除
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) : \Illuminate\Http\JsonResponse
    {

        $result = $this->service->delete($id);

        return $this->success($result);
    }
}
