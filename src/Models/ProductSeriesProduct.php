<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Traits\HasDateTimeFormatter;

class ProductSeriesProduct extends Model
{
    use HasDateTimeFormatter;

    public function series() : BelongsTo
    {
        return $this->belongsTo(ProductSeries::class, 'series_id', 'id');
    }
}
