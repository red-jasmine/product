<?php

namespace RedJasmine\Product\Domain\Series\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class ProductSeries extends Model implements OperatorInterface
{
    use HasDateTimeFormatter;

    use \RedJasmine\Support\Domain\Models\Traits\HasOwner;

    use HasOperator;

    public $incrementing = false;

    public function products() : HasMany
    {
        return $this->hasMany(ProductSeriesProduct::class, 'series_id', 'id');
    }

}
