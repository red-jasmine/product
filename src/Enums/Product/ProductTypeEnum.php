<?php

namespace RedJasmine\Product\Enums\Product;

/**
 * 商品 类型
 */
enum ProductTypeEnum: string
{
    case GOODS = 'goods'; // 实物

    case VIRTUAL = 'virtual'; // 虚拟

    public static function names() : array
    {
        return [
            self::GOODS->value   => '实物',
            self::VIRTUAL->value => '虚拟',
        ];
    }


}
