<?php

namespace RedJasmine\Product\Services\Product\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 发货类型
 */
enum ShippingTypeEnum: string
{
    use EnumsHelper;

    case LOGISTICS = 'express'; // 物流 快递

    case VIRTUAL = 'virtual'; // 虚拟

    case CARD_KEY = 'cdk'; // 卡密


    public static function labels() : array
    {
        return [
            self::LOGISTICS->value => '物流发货',
            self::VIRTUAL->value   => '虚拟发货',
            self::CARD_KEY->value  => '卡密发货',
        ];

    }
}
