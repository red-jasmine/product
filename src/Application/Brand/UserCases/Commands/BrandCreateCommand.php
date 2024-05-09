<?php

namespace RedJasmine\Product\Application\Brand\UserCases\Commands;

use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Services\Brand\Enums\BrandStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class BrandCreateCommand extends Data
{
    public int             $parent_id   = 0;
    public int             $sort        = 0;
    public string          $name;
    public ?string         $englishName = null;
    public ?string         $logo        = null;
    public ?string         $initial     = null;
    public BrandStatusEnum $status      = BrandStatusEnum::ENABLE;
    public ?array          $extends     = null;
    public bool            $is_show     = true;


    public static function attributes(...$args) : array
    {
        return [
            'name'         => '名称',
            'english_name' => '英文名称',
            'initial'      => '首字母',
            'parent_id'    => '父品牌',
            'logo'         => 'Logo',
            'sort'         => '排序',
            'is_show'      => '是否展示',
            'status'       => '状态',
            'extends'      => '扩展字段',
        ];
    }


    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'         => [ 'required', 'max:100' ],
            'parent_id'    => [ 'required', 'integer', ],
            'english_name' => [ 'sometimes', 'nullable', 'string', ],
            'initial'      => [ 'sometimes', 'nullable', 'string', ],
            'logo'         => [ 'sometimes', 'nullable', 'max:255' ],
            'sort'         => [ 'integer' ],
            'is_show'      => [ 'boolean' ],
            'status'       => [ new Enum(BrandStatusEnum::class) ],
            'extends'      => [ 'sometimes', 'nullable', 'array' ],

        ];


    }


}
