<?php

namespace RedJasmine\Product\Pipelines\Products;

use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\DataTransferObjects\ProductModifyDTO;
use RedJasmine\Product\Models\Product;
use RedJasmine\Support\Enums\BoolIntEnum;

class ProductModifyFillPipeline extends ProductFillPipeline
{
    protected function isNeedFillSkus(Product $product) : bool
    {
        /**
         * @var $productDTO ProductDTO
         */
        $productDTO = $product->getDTO();
        // 如果 现在变化
        if ($product->isDirty('is_multiple_spec')) {
            // 变成多规格的
            if ($product->is_multiple_spec === BoolIntEnum::YES) {

                return true;
            } else {
                return false;
            }
        } else if ($product->is_multiple_spec === BoolIntEnum::YES && filled($productDTO->toArray()['skus'] ?? null)) {
            return true;
        }

        return false;
    }


    /**
     * @param Product                     $product
     * @param ProductModifyDTO|ProductDTO $productDTO
     *
     * @return void
     */
    public function fillProduct(Product $product, ProductModifyDTO|ProductDTO $productDTO) : void
    {


        $data = $productDTO->toArray();
        $info = $data['info'] ?? [];
        unset($data['skus'], $data['info'], $data['parameters']);

        foreach ($data as $key => $value) {
            $product->setAttribute($key, $value);
        }

        foreach ($info as $infoKey => $infoValue) {
            $product->info->setAttribute($infoKey, $infoValue);
        }

    }




}

