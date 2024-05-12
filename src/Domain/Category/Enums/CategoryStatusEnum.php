<?php

namespace RedJasmine\Product\Domain\Category\Enums;

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
            self::ENABLE->value  => '启用',
            self::DISABLE->value => '禁用',
        ];

    }
}
