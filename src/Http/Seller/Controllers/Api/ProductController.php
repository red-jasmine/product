<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Seller\Resources\ProductResource;
use RedJasmine\Product\Services\Product\ProductService;
use Throwable;

class ProductController extends Controller
{
    public function __construct(public ProductService $service)
    {
        $this->service->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        })->setWithOwner(function () {
            return $this->getOwner();
        })->setWithOperator(function () {
            return $this->getUser();
        });
    }

    public function index() : AnonymousResourceCollection
    {
        $result = $this->service->query()->paginate();
        return ProductResource::collection($result);
    }

    /**
     * @param Request $request
     *
     * @return ProductResource
     * @throws Throwable
     */
    public function store(Request $request) : ProductResource
    {

        $result = $this->service->create($request->all());
        return new ProductResource($result);
    }

    public function show($id) : ProductResource
    {
        $result = $this->service->query()->findOrFail($id);
        return new ProductResource($result);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return ProductResource
     * @throws Throwable
     */
    public function update(Request $request, $id) : ProductResource
    {
        if ($request->isMethod('PATCH')) {
            $result = $this->service->modify($id, $request->all());
        } else {
            $result = $this->service->update($id, $request->all());
        }
        return new ProductResource($result);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Throwable
     * @throws \RedJasmine\Support\Exceptions\AbstractException
     */
    public function destroy($id) : \Illuminate\Http\JsonResponse
    {
        $result = $this->service->delete($id);
        return $this->success($result);
    }
}
