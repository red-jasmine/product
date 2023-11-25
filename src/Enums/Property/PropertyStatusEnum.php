<?php

namespace RedJasmine\Product\Enums\Property;

enum PropertyStatusEnum: int
{

    case  DISABLE = 0;  // 禁用
    case  ENABLE = 1;  // 启用


    /**
     * @return array
     */
    public static function options() : array
    {
        return [
            self::ENABLE->value  => __('enable'),
            self::DISABLE->value => __('disable'),
        ];

    }

}
