<?php

namespace RedJasmine\Product\Domain\Brand\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum BrandStatusEnum: string
{
    use EnumsHelper;

    case  ENABLE = 'enable';  // 启用
    case  DISABLE = 'disable';  // 禁用


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
