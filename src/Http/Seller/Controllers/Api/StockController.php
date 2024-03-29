<?php

namespace RedJasmine\Product\Http\Seller\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Http\Seller\Resources\ProductStockLogResource;
use RedJasmine\Product\Http\Seller\Resources\ProductStockResource;
use RedJasmine\Product\Services\Stock\StockService;

class StockController extends Controller
{
    public function __construct(public StockService $service)
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
        return ProductStockResource::collection($result);
    }

    public function show($id) : ProductStockResource
    {
        $result = $this->service->query()->findOrFail($id);
        return new ProductStockResource($result);
    }

    public function logs($id) : AnonymousResourceCollection
    {
        $result = $this->service
            ->setQueryCallbacks([])
            ->logsQuery(isRequest: true)
            ->where('product_id', (int)$id)
            ->orderBy('id', 'desc')
            ->paginate();

        return ProductStockLogResource::collection($result);
    }

}
