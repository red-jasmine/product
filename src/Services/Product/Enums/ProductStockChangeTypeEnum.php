<?php

namespace RedJasmine\Product\Services\Product\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockChangeTypeEnum: string
{

    use EnumsHelper;

    case INIT = 'init';// 卖家编辑

    case SELLER = 'seller';// 卖家编辑

    case SALE = 'sale'; // 销售


    public static function labels() : array
    {
        return [
            self::INIT->value   => '创建',
            self::SELLER->value => '编辑',
            self::SALE->value   => '销售',
        ];

    }


}
