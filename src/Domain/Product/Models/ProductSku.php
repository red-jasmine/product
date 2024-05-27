<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Traits\HasDateTimeFormatter;


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


    public function setDeleted()
    {
        $this->deleted_at = $this->deleted_at ?? now();
        $this->status     = ProductStatusEnum::DELETED;
    }

}
