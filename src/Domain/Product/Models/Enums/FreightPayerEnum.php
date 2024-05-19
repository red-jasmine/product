<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum FreightPayerEnum: string
{

    use EnumsHelper;

    case DEFAULT = 'default';

    case SELLER = 'seller';

    case BUYER = 'buyer';

    public static function labels() : array
    {
        return [
            self::DEFAULT->value => '默认',
            self::SELLER->value  => '卖家',
            self::BUYER->value   => '买家',
        ];
    }

}
