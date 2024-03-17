<?php

namespace RedJasmine\Product\Services\Property\Data;

use Illuminate\Validation\Rule;
use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;
use RedJasmine\Product\Services\Property\Rules\PropertyTitleRule;
use RedJasmine\Support\DataTransferObjects\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class PropertyGroupData extends Data
{
    public string             $name;
    public int                $sort    = 0;
    public PropertyStatusEnum $status  = PropertyStatusEnum::ENABLE;
    public ?array             $extends = null;

    public static function attributes(...$args) : array
    {

        return [
            'name'    => '名称',
            'extends' => '扩展参数',
            'sort'    => '排序',
            'status'  => '状态',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'    => [ 'required', 'max:30', new PropertyTitleRule() ],
            'extends' => [ 'sometimes', 'array' ],
            'sort'    => [ 'integer' ],
            'status'  => [ 'required', Rule::enum(PropertyStatusEnum::class) ],

        ];
    }
}
