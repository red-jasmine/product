<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Builder\ProductBuilder;
use RedJasmine\Product\Services\Product\Contracts\ProductInterface;
use RedJasmine\Support\Traits\WithUserService;
use Throwable;

class ProductService
{
    use WithUserService;

    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 999999999;


    /**
     * @param ProductInterface $productObject
     *
     * @return Product
     * @throws Throwable
     */
    public function create(ProductInterface $productObject) : Product
    {
        // 验证数据

        // 保存数据
        DB::beginTransaction();
        try {
            // 生成商品ID
            $this->validateProduct($productObject);
            $builder     = new ProductBuilder();
            $product     = new Product();
            $productInfo = new ProductInfo();
            $product->id = $product->id ?? $builder->generateID();
            // 设置属性
            $product->fill($productObject->getAttributes());
            $productInfo->fill();
            // 插件数据
            $product->info()->save($productInfo);
            $skus = [];
            $product->skus()->saveMany($skus);
            $product->refresh();
            $product->save();
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;

    }


    // 验证数据
    public function validateProduct(ProductInterface $productObject)
    {
        //

    }


    public function update()
    {

    }
}
