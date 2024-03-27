<?php

namespace RedJasmine\Product\Services\Product\Data;

use RedJasmine\Support\Data\Data;

/**
 *
 */
class StockChannelData extends Data
{

    /**
     * @param string $type
     * @param int    $id
     */
    public function __construct(
        public string $type,
        public int    $id,
    )
    {
    }

}
