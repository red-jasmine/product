<?php
namespace RedJasmine\Product\Services\Brand\Enums;

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
            self::ENABLE->value  => __('global.enable'),
            self::DISABLE->value => __('global.disable'),
        ];

    }

}
