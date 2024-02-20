<?php

namespace RedJasmine\Product\Http\Buyer\Controllers\Api;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Business\Buyer\ProductService;
use RedJasmine\Product\Http\Buyer\Resources\ProductResource;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service)
    {

        $this->service->setOwner($this->getOwner())->setOperator($this->getUser());
    }

    public function index() : AnonymousResourceCollection
    {

        $result = $this->service->queries()->lists();
        return ProductResource::collection($result);
    }

    public function show($id) : ProductResource
    {
        $result = $this->service->queries()->find($id);

        return new ProductResource($result);
    }


}
