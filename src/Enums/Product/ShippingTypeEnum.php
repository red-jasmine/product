<?php

namespace RedJasmine\Product\Enums\Product;

/**
 * 发货类型
 */
enum ShippingTypeEnum: string
{
    case LOGISTICS = 'express'; // 物流 快递

    case VIRTUAL = 'virtual'; // 虚拟

    case CARD_KEY = 'card_key'; // 卡密


    public static function names() : array
    {
        return [
            self::LOGISTICS->value => '物流发货',
            self::VIRTUAL->value   => '虚拟发货',
            self::CARD_KEY->value  => '自动发货(卡密/网盘)',
        ];

    }
}
