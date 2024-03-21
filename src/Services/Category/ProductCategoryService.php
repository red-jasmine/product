<?php

namespace RedJasmine\Product\Services\Category;

use RedJasmine\Product\Enums\Category\CategoryStatusEnum;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Product\Models\ProductCategory as Model;
use RedJasmine\Product\Services\Category\Data\ProductCategoryData;
use RedJasmine\Product\Services\Category\Validators\CategoryValidatorManage;
use RedJasmine\Support\Foundation\Service\ResourceService;

/**
 * 商品类目服务
 */
class ProductCategoryService extends ResourceService
{
    public static string $model = Model::class;

    public static string $dataClass = ProductCategoryData::class;

    public static ?string $validatorManageClass = CategoryValidatorManage::class;

    protected static ?string $actionsConfigKey = 'red-jasmine.product.services.category.actions';

    public static ?string $actionPipelinesConfigPrefix = 'red-jasmine.product.services.category.pipelines';


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
        $model = $this->query(false)->findOrFail($id);
        if ($model->isAllowUse()) {
            return $model;
        }
        throw new CategoryException('类目不可使用');
    }


}
