<?php

namespace RedJasmine\Product\Services\Category\Data;

use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Models\ProductSellerCategory as Model;
use RedJasmine\Product\Services\Category\Enums\CategoryStatusEnum;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductCategoryData extends Data
{

    public function __construct(
        public string             $name,
        public int                $parent_id = 0,
        public CategoryStatusEnum $status = CategoryStatusEnum::ENABLE,
        public int                $sort = 0,
        public bool               $is_leaf = false,
        public bool               $is_show = false,
        public string|null        $group_name = null,
        public string|null        $image = null,
        public array|null         $extends = null,
    )
    {
    }


    public static function attributes() : array
    {
        return [
            'parent_id'  => __('red-jasmine/product::product-category.fields.parent_id'),
            'name'       => __('red-jasmine/product::product-category.fields.name'),
            'group_name' => __('red-jasmine/product::product-category.fields.group_name'),
            'sort'       => __('red-jasmine/product::product-category.fields.sort'),
            'is_leaf'    => __('red-jasmine/product::product-category.fields.is_leaf'),
            'is_show'    => __('red-jasmine/product::product-category.fields.is_show'),
            'status'     => __('red-jasmine/product::product-category.fields.status'),
            'extends'    => __('red-jasmine/product::product-category.fields.extends'),
        ];
    }


    public static function rules(ValidationContext $context) : array
    {

        return [
            'id'         => [],
            'parent_id'  => [ 'integer' ],
            'name'       => [ 'required', 'string', 'max:100' ],
            'group_name' => [ 'sometimes', 'nullable', 'max:100' ],
            'image'      => [ 'sometimes', 'nullable', 'max:255' ],
            'sort'       => [ 'integer' ],
            'is_leaf'    => [ 'required', 'boolean' ],
            'is_show'    => [ 'required', 'boolean' ],
            'status'     => [ new Enum(CategoryStatusEnum::class) ],
            'extends'    => [ 'sometimes', 'nullable', 'array' ],
        ];

    }


}
