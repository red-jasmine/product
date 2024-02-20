<?php

namespace RedJasmine\Product\Services\Category;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Exceptions\SellerCategoryException;
use RedJasmine\Product\Models\ProductSellerCategory as Model;
use RedJasmine\Product\Services\Category\Validators\Rules\CategoryParentRule;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Foundation\Service\WithUserService;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use RedJasmine\Support\Rules\ParentIDValidationRule;

/**
 * 商品类目服务
 */
class ProductSellerCategoryService
{

    use WithUserService;

    use HasQueryBuilder;

    /**
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function query()
    {
        return $this->queryBuilder();
    }

    public string $model = Model::class;


    public function find(int $id) : Model
    {
        return $this->query()->findOrFail($id);
    }


    /**
     * @param int $id
     *
     * @return Model
     * @throws SellerCategoryException
     */
    public function isAllowUse(int $id) : Model
    {
        $model = $this->find($id);
        if ($model->isAllowUse()) {
            return $model;
        }
        throw new SellerCategoryException('当前分类不支持使用');
    }


    /**
     * to tree
     * @return array
     */
    public function tree() : array
    {



        $query = (new Model())->withQuery(function (Model $query) {
            return $query->where('status', CategoryStatusEnum::ENABLE)
                         ->onlyOwner($this->getOwner());
        });

        return $query->toTree();
    }

    public function selectOptions()
    {
        return Model::selectOptions(function ($query) {
            return $query->onlyOwner($this->getOwner());
        });
    }

    /**
     * @param array $attributes
     *
     * @return Model
     * @throws Exception
     */
    public function create(array $attributes) : Model
    {
        $productCategory     = new Model();
        $productCategory->id = Snowflake::getInstance()->nextId();
        $validator           = $this->validator($attributes);
        $validator->validate();
        $productCategory->fill($validator->safe()->all());
        $productCategory->owner   = $this->getOwner();
        $productCategory->creator = $this->getOperator();
        $productCategory->save();
        return $productCategory;
    }

    public function validator(array $attributes) : \Illuminate\Validation\Validator
    {
        return Validator::make($attributes, $this->rules(), [], $this->attributes());
    }

    protected function rules() : array
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
            'status'     => [ new Enum(CategoryStatusEnum::class) ],
            'extends'    => [ 'sometimes', 'array' ],
        ];

    }

    protected function attributes() : array
    {
        return [
            'id'           => __('red-jasmine/product::product-seller-category.attributes.id'),
            'parent_id'    => __('red-jasmine/product::product-seller-category.attributes.parent_id'),
            'name'         => __('red-jasmine/product::product-seller-category.attributes.name'),
            'group_name'   => __('red-jasmine/product::product-seller-category.attributes.group_name'),
            'sort'         => __('red-jasmine/product::product-seller-category.attributes.sort'),
            'is_leaf'      => __('red-jasmine/product::product-seller-category.attributes.is_leaf'),
            'status'       => __('red-jasmine/product::product-seller-category.attributes.status'),
            'extends'      => __('red-jasmine/product::product-seller-category.attributes.extends'),
            'creator_type' => __('red-jasmine/product::product-seller-category.attributes.creator_type'),
            'creator_id'   => __('red-jasmine/product::product-seller-category.attributes.creator_id'),
            'updater_type' => __('red-jasmine/product::product-seller-category.attributes.updater_type'),
            'updater_id'   => __('red-jasmine/product::product-seller-category.attributes.updater_id'),
        ];
    }

    /**
     * 更新操作
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return Model
     */
    public function update(int $id, array $attributes) : Model
    {
        $productCategory = Model::findOrFail($id);
        $validator       = $this->validator($attributes);
        $validator->addRules([ 'parent_id' => [ new ParentIDValidationRule($id) ] ]);
        $validator->validated();
        $productCategory->fill($validator->safe()->all());
        $productCategory->updater = $this->getOperator();
        $productCategory->save();
        return $productCategory;
    }

    /**
     * 修改
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return Model
     */
    public function modify(int $id, array $attributes) : Model
    {
        $productCategory = Model::findOrFail($id);
        $validator       = $this->validator($attributes);
        $validator->addRules([ 'parent_id' => [ new ParentIDValidationRule($id) ] ]);
        $validator->setRules(Arr::only($validator->getRules(), array_keys($attributes)));
        $validator->validated();
        $productCategory->fill($validator->safe()->all());
        $productCategory->updater = $this->getOperator();
        $productCategory->save();
        return $productCategory;

    }


    /**
     * 删除
     *
     * @param int $id
     *
     * @return bool|null
     */
    public function delete(int $id) : ?bool
    {
        return Model::where('id', $id)->delete();
    }
}
