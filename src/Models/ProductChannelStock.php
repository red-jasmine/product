<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Services\Product\Data\StockChannelData;
use RedJasmine\Product\Services\Product\Stock\StockChannelInterface;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOwner;

/**
 * 渠道库存
 */
class ProductChannelStock extends Model
{

    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasOwner;


    public function scopeChannel(Builder $query, StockChannelData $channel) : Builder
    {
        return $query->where('channel_type', $channel->type)->where('channel_id', $channel->id);
    }
}
