<?php

namespace RedJasmine\Product\Services\Category\Validators;

use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Models\ProductSellerCategory as Model;
use RedJasmine\Product\Services\Category\Enums\CategoryStatusEnum;
use RedJasmine\Product\Services\Category\Validators\Rules\CategoryParentRule;
use RedJasmine\Support\Foundation\ValidatorManage;
use RedJasmine\Support\Rules\NotZeroExistsRule;

class SellerCategoryValidatorManage extends ValidatorManage
{

    public function attributes() : array
    {
        return [
            'id'           => __('red-jasmine/product::product-seller-category.fields.id'),
            'parent_id'    => __('red-jasmine/product::product-seller-category.fields.parent_id'),
            'name'         => __('red-jasmine/product::product-seller-category.fields.name'),
            'group_name'   => __('red-jasmine/product::product-seller-category.fields.group_name'),
            'sort'         => __('red-jasmine/product::product-seller-category.fields.sort'),
            'is_leaf'      => __('red-jasmine/product::product-seller-category.fields.is_leaf'),
            'status'       => __('red-jasmine/product::product-seller-category.fields.status'),
            'extends'      => __('red-jasmine/product::product-seller-category.fields.extends'),
            'creator_type' => __('red-jasmine/product::product-seller-category.fields.creator_type'),
            'creator_id'   => __('red-jasmine/product::product-seller-category.fields.creator_id'),
            'updater_type' => __('red-jasmine/product::product-seller-category.fields.updater_type'),
            'updater_id'   => __('red-jasmine/product::product-seller-category.fields.updater_id'),
        ];
    }

    public function rules() : array
    {
        $table = (new Model())->getTable();
        return [
            'id'         => [],
            'parent_id'  => [ 'required', 'integer', new NotZeroExistsRule($table, 'id'), new CategoryParentRule($table) ],
            'name'       => [ 'required', 'max:100' ],
            'group_name' => [ 'sometimes', 'max:100' ],
            'image'      => [ 'sometimes', 'max:255' ],
            'sort'       => [ 'integer' ],
            'is_leaf'    => [ 'required', 'boolean' ],
            'is_show'    => [ 'required', 'boolean' ],
            'status'     => [ new Enum(CategoryStatusEnum::class) ],
            'extends'    => [ 'sometimes', 'nullable', 'array' ],
        ];

    }

}
