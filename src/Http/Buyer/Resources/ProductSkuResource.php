<?php

namespace RedJasmine\Product\Http\Buyer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin  ProductSku
 */
class ProductSkuResource extends JsonResource
{

    use WithCollectionResource;

    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'price'           => $this->price,
            'market_price'    => $this->market_price,
            'properties'      => $this->properties,
            'properties_name' => $this->properties_name,
            'sales'           => $this->sales,
            'stock'           => $this->stock,
            'status'          => $this->status,
            'image'           => $this->image,
            'barcode'         => $this->barcode,
            'outer_id'        => $this->outer_id
        ];
    }
}
