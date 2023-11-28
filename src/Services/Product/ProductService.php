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
     *
     *
     * @param array $data
     *
     * @return Product
     * @throws Throwable
     */
    public function create(array $data) : Product
    {
        //

        // 保存数据
        //DB::beginTransaction();
        try {
            // 生成商品ID

            $product     = new Product();
            $productInfo = new ProductInfo();

            $builder     = new ProductBuilder();
            $product->id = $product->id ?? $builder->generateID();

            $data = $builder->validate($data);

            foreach ($data as $key => $value) {
                switch ($key) {
                    case 'info':
                        foreach ($value as $infoKey => $infoValue) {
                            $productInfo->setAttribute($infoKey, $infoValue);
                        }
                        break;
                    case 'skus':
                        break;
                    default:
                        $product->setAttribute($key, $value);
                        break;
                }
            }


            $product->withOwner($this->getOwner());
            $product->save();
            $product->info()->save($productInfo);
            dd($product);

            $skus = [];
            $product->skus()->saveMany($skus);

            $product->save();


            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $product;

    }


// 验证数据
    public
    function validateProduct($data)
    {
        //

    }


    public
    function update()
    {

    }
}
