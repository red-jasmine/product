<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Services\Stock\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Services\Stock\Enums\ProductStockTypeEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;


class ProductStockLog extends Model
{

    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    protected $casts = [
        'type'        => ProductStockTypeEnum::class,
        'change_type' => ProductStockChangeTypeEnum::class,
        'extends'     => 'array'
    ];


    public function sku() : BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'sku_id', 'id');
    }

}
