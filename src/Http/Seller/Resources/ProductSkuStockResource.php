<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin  ProductSku
 */
class ProductSkuStockResource extends JsonResource
{

    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'barcode'         => $this->barcode,
            'outer_id'        => $this->outer_id,
            'properties'      => $this->properties,
            'properties_name' => $this->properties_name,
            'stock'           => $this->stock,
            'lock_stock'      => $this->lock_stock,
            'safety_stock'    => $this->safety_stock,
            'status'          => $this->status,
        ];
    }
}
