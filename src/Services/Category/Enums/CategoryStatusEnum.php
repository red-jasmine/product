<?php

namespace RedJasmine\Product\Services\Category\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CategoryStatusEnum: string
{

    use EnumsHelper;

    case DISABLE = 'disable';


    case ENABLE = 'enable';

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
