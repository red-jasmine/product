<?php

namespace RedJasmine\Product\Services\Stock\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockTypeEnum: string
{

    use EnumsHelper;

    case RESET = 'reset';
    case INIT = 'init';
    case ADD = 'add';
    case SUB = 'sub';
    case LOCK = 'lock';
    case UNLOCK = 'unlock';
    case CONFIRM = 'confirm';


    public static function labels() : array
    {
        return [
            self::RESET->value   => '重置',
            self::INIT->value    => '初始化',
            self::ADD->value     => '增加',
            self::SUB->value     => '扣减',
            self::LOCK->value    => '锁定',
            self::UNLOCK->value  => '解锁',
            self::CONFIRM->value => '确认',

        ];

    }


}
