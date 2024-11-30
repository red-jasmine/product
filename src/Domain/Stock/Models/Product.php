<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class Product extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOwner;

    use HasOperator;


    public function getTable() : string
    {
        return config('red-jasmine-product.tables.prefix','jasmine_') . Str::snake(Str::pluralStudly(class_basename($this)));;
    }

    protected $casts = [
        'status' => ProductStatusEnum::class
    ];


    public function skus() : HasMany
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

}
