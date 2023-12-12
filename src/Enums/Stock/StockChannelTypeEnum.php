<?php

namespace RedJasmine\Product\Enums\Stock;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum StockChannelTypeEnum: string
{

    use EnumsHelper;

    case  ACTIVITY = 'activity';
    case  LIVE = 'live';
    case  CUSTOM = 'custom';


    public static function names() : array
    {
        return [
            self::ACTIVITY->value => '活动',
            self::LIVE->value     => '直播',
            self::CUSTOM->value   => '自定义',
        ];

    }


}
