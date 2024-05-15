<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyCreateCommand extends Data
{
    public string             $name;
    public int                $sort       = 0;
    public int                $groupId    = 0;
    public PropertyStatusEnum $status     = PropertyStatusEnum::ENABLE;
    public ?array             $extendInfo = null;

    public static function attributes(...$args) : array
    {

        return [
            'name'     => '属性名',
            'extends'  => '扩展参数',
            'sort'     => '排序',
            'group_id' => '分组',
            'sort'     => '排序值',

        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'        => [ 'required', 'max:30', ],
            'extend_info' => [ 'sometimes', 'nullable', 'array' ],
            'sort'        => [ 'integer' ],
            'group_id'    => [ 'sometimes', 'nullable', 'integer' ],

        ];
    }
}
