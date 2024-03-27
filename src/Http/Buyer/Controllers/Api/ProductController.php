<?php

namespace RedJasmine\Product\Http\Buyer\Controllers\Api;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use RedJasmine\Product\Application\Buyer\Services\ProductService;
use RedJasmine\Product\Http\Buyer\Resources\ProductResource;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service)
    {
    }

    public function index() : AnonymousResourceCollection
    {
        $query  = $this->service->query(true);
        $result = $query->paginate();
        return ProductResource::collection($result);
    }

    public function show($id) : ProductResource
    {

        $result = $this->service->query()->findOrFail($id);
        return new ProductResource($result);
    }


}
