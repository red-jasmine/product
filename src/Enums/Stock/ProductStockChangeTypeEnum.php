<?php

namespace RedJasmine\Product\Enums\Stock;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockChangeTypeEnum: string
{

    use EnumsHelper;

    case SELLER = 'seller';// 卖家编辑


    case SALE = 'sale'; // 销售


    public static function names() : array
    {
        return [
            self::SELLER->value => '卖家编辑',
            self::SALE->value   => '商品销售',
        ];

    }


}
