<?php

namespace RedJasmine\Product\Domain\Series\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOwner;

class ProductSeries extends Model
{
    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    public $incrementing = false;

    public function products() : HasMany
    {
        return $this->hasMany(ProductSeriesProduct::class, 'series_id', 'id');
    }

}
