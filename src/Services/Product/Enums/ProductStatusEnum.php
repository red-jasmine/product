<?php

namespace RedJasmine\Product\Services\Product\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatusEnum: string
{
    use EnumsHelper;

    case ON_SALE = 'on_sale'; // 在售

    case SOLD_OUT = 'sold_out'; // 售停

    case OFF_SHELF = 'off_shelf'; // 下架

    case PRE_SALE = 'pre_sale'; // 预售

    case FORBID = 'forbid_sale'; // 禁售

    case DELETED = 'deleted'; // 删除 仅在 sku 中使用

    public static function labels() : array
    {
        return [
            self::ON_SALE->value   => '在售',
            self::SOLD_OUT->value  => '停售',
            self::OFF_SHELF->value => '下架',
            self::PRE_SALE->value  => '预售',
            self::FORBID->value    => '禁售',
        ];

    }

    public static function skusStatus() : array
    {
        return [
            self::ON_SALE->value  => '在售',
            self::SOLD_OUT->value => '售停',
        ];
    }

}
