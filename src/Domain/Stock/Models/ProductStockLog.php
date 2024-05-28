<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;


class ProductStockLog extends Model
{

    public $incrementing = false;

    use HasDateTimeFormatter;

    use \RedJasmine\Support\Domain\Models\Traits\HasOwner;

    use HasOperator;

    protected $casts = [
        'type'        => ProductStockTypeEnum::class,
        'change_type' => ProductStockChangeTypeEnum::class,
        'expands'     => 'array'
    ];


    public function sku() : BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'sku_id', 'id');
    }

}
