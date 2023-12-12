<?php

namespace RedJasmine\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Services\Product\Stock\StockChannelInterface;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\WithOwnerModel;

/**
 * 渠道库存
 * 逻辑库存
 */
class ProductChannelStock extends Model
{

    public $incrementing = false;

    use HasDateTimeFormatter;

    use WithOwnerModel;


    public function scopeChannel(Builder $query, StockChannelInterface $channel) : Builder
    {
        return $query->where('channel_type', $channel->channelType())
                     ->where('channel_id', $channel->channelID());
    }
}
