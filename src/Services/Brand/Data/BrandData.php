<?php

namespace RedJasmine\Product\Services\Brand\Data;

use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Models\Brand;
use RedJasmine\Product\Services\Brand\Enums\BrandStatusEnum;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class BrandData extends Data
{
    public int             $parent_id    = 0;
    public int             $sort         = 0;
    public string          $name;
    public ?string         $english_name = null;
    public ?string         $logo         = null;
    public BrandStatusEnum $status       = BrandStatusEnum::ENABLE;
    public ?array          $extends      = null;
    public bool            $is_show      = true;


    public static function attributes(...$args) : array
    {
        return [
            'name'         => '名称',
            'english_name' => '英文名称',
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
        $table = (new Brand())->getTable();
        return [
            'name'         => [ 'required', 'max:100' ],
            'parent_id' => [ 'required', 'integer', new NotZeroExistsRule($table, 'id'), ],
            'english_name' => [ 'sometimes', 'nullable', 'integer', ],
            'logo'         => [ 'sometimes', 'nullable', 'max:255' ],
            'sort'         => [ 'integer' ],
            'is_show'      => [ 'boolean' ],
            'status'       => [ new Enum(BrandStatusEnum::class) ],
            'extends'      => [ 'sometimes', 'nullable', 'array' ],

        ];


    }


}
