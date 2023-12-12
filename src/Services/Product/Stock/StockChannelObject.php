<?php

namespace RedJasmine\Product\Services\Product\Stock;

class StockChannelObject implements StockChannelInterface
{
    public function __construct(protected string $type, protected int $id)
    {
    }


    public function channelType() : string
    {
        return $this->type;
    }

    public function channelID() : int
    {
        return $this->id;
    }


}
