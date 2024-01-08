<?php

namespace RedJasmine\Product\Enums\Property;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PropertyStatusEnum: int
{


    use EnumsHelper;

    case  DISABLE = 0;  // 禁用
    case  ENABLE = 1;  // 启用


    /**
     * @return array
     */
    public static function labels() : array
    {
        return [
            self::ENABLE->value  => __('global.enable'),
            self::DISABLE->value => __('global.disable'),
        ];

    }

}
