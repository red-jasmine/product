<?php

namespace RedJasmine\Product\Http\Buyer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin ProductSeries
 */
class ProductSeriesResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'products' => ProductSeriesProductResource::collection($this->products),
        ];
    }
}
