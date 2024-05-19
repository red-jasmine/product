<?php

namespace RedJasmine\Product\Http\Seller\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Support\Http\Resources\WithCollectionResource;

/**
 * @mixin ProductStockLog
 */
class ProductStockLogResource extends JsonResource
{
    use WithCollectionResource;

    public function toArray(Request $request) : array
    {

        return [
            'id'            => $this->id,
            'product_id'    => $this->product_id,
            'sku_id'        => $this->sku_id,
            'type'          => $this->type,
            'change_type'   => $this->change_type,
            'change_detail' => $this->change_detail,
            'channel_type'  => $this->channel_type,
            'channel_id'    => $this->channel_id,
            'stock'         => $this->stock,
            'lock_stock'    => $this->lock_stock,
            'extends'       => $this->extends,
            'creator_type'  => $this->creator_type,
            'creator_id'    => $this->creator_id,
            'created_at'    => $this->created_at,
        ];
    }
}
