<?php

namespace RedJasmine\Product\Services\Product\Stock;

interface StockChannelInterface
{


    /**
     * 渠道类型
     * @return string
     */
    public function channelType() : string;


    /**
     * 渠道ID
     * @return int
     */
    public function channelID() : int;

}
