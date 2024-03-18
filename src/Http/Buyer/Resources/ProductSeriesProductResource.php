<?php

namespace RedJasmine\Product\Http\Buyer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\ProductSeries;
use RedJasmine\Product\Models\ProductSeriesProduct;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin ProductSeriesProduct
 */
class ProductSeriesProductResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [
            'product_id' => $this->product_id,
            'name'       => $this->name
        ];
    }
}