<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum DeliveryTypeEnum: string
{
    use EnumsHelper;

    case EXPRESS = 'express';
    case CITY = 'city';
    case STORE = 'store';

    public static function labels() : array
    {
        return [
            self::EXPRESS->value => '物流快递',
            self::CITY->value    => '同城配送',
            self::STORE->value   => '门店自提',
        ];

    }
}
