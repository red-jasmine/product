<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Services\Product\Enums\ProductStockChangeTypeEnum;
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
        'change_type' => ProductStockChangeTypeEnum::class
    ];


    public function sku() : BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'sku_id', 'id');
    }

}
