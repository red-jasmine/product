<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Business\Seller\ProductService;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\DataTransferObjects\ProductModifyDTO;
use RedJasmine\Product\Http\Seller\Resources\ProductResource;
use Throwable;

class ProductController extends Controller
{
    public function __construct(public ProductService $service)
    {
        $this->service
            ->setOwner($this->getOwner())
            ->setOperator($this->getUser());
    }

    public function index() : AnonymousResourceCollection
    {
        $result = $this->service->queries()->lists();
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
        $dto        = ProductDTO::from($request->all());
        $dto->owner = $this->service->getOwner();

        $result = $this->service->create($dto);
        return new ProductResource($result);
    }

    public function show($id) : ProductResource
    {
        $result = $this->service->queries()->find($id);
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
            $dto        = ProductModifyDTO::from($request->all());
            $dto->owner = $this->service->getOwner();

            $result = $this->service->modify($id, $dto);
        } else {
            $dto        = ProductDTO::from($request->all());
            $dto->owner = $this->service->getOwner();
            $result     = $this->service->update($id, $dto);
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
