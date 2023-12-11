<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOperatorModel;
use RedJasmine\Support\Traits\Models\WithOwnerModel;


class ProductStockLog extends Model
{

    public $incrementing = false;

    use HasDateTimeFormatter;

    use WithOwnerModel;

    use WithOperatorModel;

    protected $casts = [
        'change_type' => ProductStockChangeTypeEnum::class
    ];


    public function sku() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'sku_id', 'id');
    }

}
