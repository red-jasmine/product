<?php

namespace RedJasmine\Product\Domain\Stock\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockTypeEnum: string
{

    use EnumsHelper;

    case SET = 'set';
    case ADD = 'add';
    case SUB = 'sub';
    case LOCK = 'lock';
    case UNLOCK = 'unlock';
    case CONFIRM = 'confirm';


    public static function labels() : array
    {
        return [
            self::SET->value     => '设置',
            self::ADD->value     => '增加',
            self::SUB->value     => '扣减',
            self::LOCK->value    => '锁定',
            self::UNLOCK->value  => '解锁',
            self::CONFIRM->value => '确认',

        ];

    }


}
