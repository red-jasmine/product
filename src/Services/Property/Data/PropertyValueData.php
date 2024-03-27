<?php

namespace RedJasmine\Product\Services\Property\Data;

use Illuminate\Validation\Rule;
use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;
use RedJasmine\Product\Services\Property\Rules\PropertyTitleRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class PropertyValueData extends Data
{
    public int                $pid;
    public string             $name;
    public int                $sort     = 0;
    public ?int               $group_id = null;
    public PropertyStatusEnum $status   = PropertyStatusEnum::ENABLE;
    public ?array             $extends  = null;

    public static function attributes(...$args) : array
    {

        return [
            'pid'      => '属性ID',
            'name'     => '名称',
            'extends'  => '扩展参数',
            'sort'     => '排序',
            'group_id' => '分组',
            'sort'     => '排序值',

        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'pid'      => [ 'required', 'integer', Rule::exists('product_properties', 'pid') ],
            'name'     => [ 'required', 'max:30', new PropertyTitleRule() ],
            'extends'  => [ 'sometimes', 'array' ],
            'sort'     => [ 'integer' ],
            'group_id' => [ 'sometimes', 'nullable', 'integer' ],
            'name'     => [ 'required', new PropertyTitleRule() ],

        ];
    }
}
