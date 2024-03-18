<?php

namespace RedJasmine\Product\Services\Category\Data;

use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Models\ProductSellerCategory as Model;
use RedJasmine\Product\Services\Category\Enums\CategoryStatusEnum;
use RedJasmine\Product\Services\Category\Validators\Rules\CategoryParentRule;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\DataTransferObjects\UserData;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use RedJasmine\Support\Rules\ParentIDValidationRule;


class ProductSellerCategoryData extends Data
{

    public UserData           $owner;
    public string             $name;
    public CategoryStatusEnum $status     = CategoryStatusEnum::ENABLE;
    public int                $parent_id  = 0;
    public int                $sort       = 0;
    public bool               $is_leaf    = false;
    public string|null        $group_name = null;
    public string|null        $image      = null;


    public static function attributes() : array
    {
        return [
            'id'           => __('red-jasmine/product::product-seller-category.attributes.id'),
            'parent_id'    => __('red-jasmine/product::product-seller-category.attributes.parent_id'),
            'name'         => __('red-jasmine/product::product-seller-category.attributes.name'),
            'group_name'   => __('red-jasmine/product::product-seller-category.attributes.group_name'),
            'sort'         => __('red-jasmine/product::product-seller-category.attributes.sort'),
            'is_leaf'      => __('red-jasmine/product::product-seller-category.attributes.is_leaf'),
            'is_show'      => __('red-jasmine/product::product-seller-category.attributes.is_show'),
            'status'       => __('red-jasmine/product::product-seller-category.attributes.status'),
            'extends'      => __('red-jasmine/product::product-seller-category.attributes.extends'),
            'creator_type' => __('red-jasmine/product::product-seller-category.attributes.creator_type'),
            'creator_id'   => __('red-jasmine/product::product-seller-category.attributes.creator_id'),
            'updater_type' => __('red-jasmine/product::product-seller-category.attributes.updater_type'),
            'updater_id'   => __('red-jasmine/product::product-seller-category.attributes.updater_id'),
        ];
    }


    public static function rules() : array
    {
        $table = (new Model())->getTable();
        return [
            'id'         => [],
            'parent_id'  => [ 'required', 'integer',
                              new NotZeroExistsRule($table, 'id'), new CategoryParentRule($table) ],
            'name'       => [ 'required', 'max:100' ],
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
