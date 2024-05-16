<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum FreightPayerEnum: int
{

    use EnumsHelper;

    case DEFAULT = 0;

    case SELLER = 1;

    case BUYER = 2;

    public static function labels() : array
    {
        return [
            self::DEFAULT->value => '默认',
            self::SELLER->value  => '卖家',
            self::BUYER->value   => '买家',
        ];
    }

}
