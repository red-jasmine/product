<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin  \RedJasmine\Product\Domain\Product\Models\Product
 */
class ProductStockResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {


        $skus = null;
        if ($this->relationLoaded('skus')) {
            $skus = ProductSkuStockResource::collection($this->skus);
        }
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'barcode'      => $this->barcode,
            'outer_id'     => $this->outer_id,
            'stock'        => $this->stock,
            'lock_stock'   => $this->lock_stock,
            'safety_stock' => $this->safety_stock,
            'skus'         => $skus,
        ];
    }
}
