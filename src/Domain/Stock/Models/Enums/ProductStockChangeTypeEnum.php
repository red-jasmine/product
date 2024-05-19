<?php

namespace RedJasmine\Product\Domain\Stock\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockChangeTypeEnum: string
{

    use EnumsHelper;

    case INIT = 'init';// 初始化

    case SELLER = 'seller';// 卖家编辑

    case SALE = 'sale'; // 销售


    public static function labels() : array
    {
        return [
            self::INIT->value   => '初始化',
            self::SELLER->value => '编辑',
            self::SALE->value   => '销售',
        ];

    }


}
