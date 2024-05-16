<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;

class ProductSku extends Model
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    public $incrementing = false;


    protected $casts = [
        'status'        => ProductStatusEnum::class,// 状态
        'modified_time' => 'datetime',
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


}
