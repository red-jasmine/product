<?php

namespace RedJasmine\Product\Services\Category;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Product\Models\ProductCategory as Model;
use RedJasmine\Product\Services\Category\Data\ProductCategoryData;
use RedJasmine\Product\Services\Category\Data\ProductSellerCategoryData;
use RedJasmine\Product\Services\Category\Validators\CategoryValidatorManage;
use RedJasmine\Product\Services\Category\Validators\Rules\CategoryParentRule;
use RedJasmine\Support\Foundation\Service\ResourceService;

use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use RedJasmine\Support\Rules\ParentIDValidationRule;

/**
 * 商品类目服务
 */
class ProductCategoryService extends ResourceService
{
    public static string $model = Model::class;

    public static string $dataClass = ProductCategoryData::class;

    public static ?string $validatorManageClass = CategoryValidatorManage::class;

    protected static ?string $actionsConfigKey = 'red-jasmine.product.actions.category';

    public static bool $autoModelWithOwner = false;


    public static bool $modelWithOwner = false;

    public function find(int $id) : Model
    {
        return Model::findOrFail($id);
    }

    /**
     * @param int $id
     *
     * @return Model
     * @throws CategoryException
     */
    public function isAllowUse(int $id) : Model
    {
        $model = $this->find($id);
        if ($model->isAllowUse()) {
            return $model;
        }
        throw new CategoryException('类目不可使用');
    }


}
