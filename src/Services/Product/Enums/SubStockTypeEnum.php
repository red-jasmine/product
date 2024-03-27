<?php

namespace RedJasmine\Product\Services\Product\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SubStockTypeEnum: int
{
    use EnumsHelper;

    case DEFAULT = 0;

    case ORDER = 1;

    case PAYMENT = 2;


    public static function labels() : array
    {
        return [
            self::DEFAULT->value => '默认',
            self::ORDER->value   => '下单',
            self::PAYMENT->value => '付款',
        ];

    }

}
