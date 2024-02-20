<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;

class ProductSeries extends Model
{
    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    public function products() : HasMany
    {
        return $this->hasMany(ProductSeriesProduct::class, 'series_id', 'id');
    }
}
