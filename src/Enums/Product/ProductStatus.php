<?php

namespace RedJasmine\Product\Enums\Product;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatus: string
{
    use EnumsHelper;

    case ON_SALE = 'on_sale'; // 在售

    case OUT_OF_STOCK = 'out_of_stock'; // 缺货

    case SOLD_OUT = 'sold_out'; // 售停

    case IN_STOCK = 'in_stock'; // 仓库中

    case OFF_SHELF = 'off_shelf'; // 下架

    case PRE_SALE = 'pre_sale'; // 预售

    case FORCED_OFF_SHELF = 'forced_off_shelf'; // 强制下架

    case DELETED = 'deleted'; // 删除


    public static function names():array
    {
        return [
            self::ON_SALE->value          => '在售',
            self::OUT_OF_STOCK->value     => '缺货',
            self::SOLD_OUT->value         => '售停',
            self::IN_STOCK->value         => '仓库中',
            self::OFF_SHELF->value        => '下架',
            self::PRE_SALE->value         => '预售',
            self::FORCED_OFF_SHELF->value => '强制下架',
            self::DELETED->value          => '删除',
        ];

    }


}
