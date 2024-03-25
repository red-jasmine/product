<?php

namespace RedJasmine\Product\Services\Category;

use RedJasmine\Product\Exceptions\SellerCategoryException;
use RedJasmine\Product\Models\ProductSellerCategory as Model;
use RedJasmine\Product\Services\Category\Actions\CategoryUpdateAction;
use RedJasmine\Product\Services\Category\Data\ProductSellerCategoryData;
use RedJasmine\Product\Services\Category\Validators\CategoryValidatorManage;
use RedJasmine\Product\Services\Category\Validators\SellerCategoryValidatorManage;
use RedJasmine\Support\Foundation\Service\ResourceService;

/**
 * 商品类目服务
 */
class ProductSellerCategoryService extends ResourceService
{
    // 如果资源服务设置了所属人 那么将对资源进行
    public static string $model = Model::class;

    public static string $dataClass = ProductSellerCategoryData::class;

    public static ?string $validatorManageClass = SellerCategoryValidatorManage::class;


    protected static ?string $actionsConfigKey = 'red-jasmine.product.services.seller-category.actions';

    public static ?string $actionPipelinesConfigPrefix = 'red-jasmine.product.services.seller-category.pipelines';

    public static bool $autoModelWithOwner = true;


    /**
     * @param int $id
     *
     * @return Model
     * @throws SellerCategoryException
     */
    public function isAllowUse(int $id) : Model
    {

        $model = $this->query()->findOrFail($id);

        if ($model->isAllowUse()) {
            return $model;
        }
        throw new SellerCategoryException('当前分类不支持使用');
    }


    public function selectOptions() : array
    {
        return Model::selectOptions(function ($query) {
            return $query->onlyOwner($this->getOwner());
        });
    }


}
