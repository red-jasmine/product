<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\StockInitCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Property\PropertyFormatter;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;

class ProductCreateCommandHandler extends ProductCommand
{




    // 需要组合 品牌服务、分类服务、卖家分类服务、属性服务

    public function handle(ProductCreateCommand $command) : Product
    {

        DB::beginTransaction();
        try {

            /**
             * @var $product Product
             */
            $product = $this->getService()->newModel($command);

            // 基础验证
            // 验证 销售属性和 规格一一对应
            $this->fillProduct($product, $command);


            if ($product->brand_id) {
                $this->brandQueryService->find($product->brand_id);
            }


            // 多规格区别处理
            switch ($command->isMultipleSpec) {
                case true: // 多规格

                    $saleProps = $this->propertyFormatter->formatArray($command->saleProps->toArray());
                    // 规格验证 TODO
                    $product->info->sale_props = $saleProps;

                    // 可选  后续考虑 TODO
                    //$defaultSku             = $this->defaultSku($product);
                    //$defaultSku->deleted_at = now();
                    //$product->addSku($defaultSku);

                    $command->skus?->each(function ($skuData) use ($product) {
                        $sku     = new ProductSku();
                        $sku->id = $this->getService()->buildId();
                        $this->fillSku($sku, $skuData);
                        $product->addSku($sku);
                    });


                    break;
                case false: // 单规格

                    $product->info->sale_props = [];
                    $sku                       = $this->defaultSku($product);
                    $product->addSku($sku);
                    break;
            }

            $this->execute(
                execute: null,
                persistence: fn() => $this->getService()->getRepository()->store($product)
            );

            // 操作库存
            $skuCommand = $command->skus?->keyBy('properties');

            foreach ($product->skus as $sku) {
                if ($sku->deleted_at) {
                    // 删除库存
                    continue;
                }
                $stock = $skuCommand[$sku->properties]?->stock ?? $command->stock;
                $this->stockCommandService->init(StockInitCommand::from(
                    [
                        'product_id'  => $product->id,
                        'sku_id'      => $sku->id,
                        'stock'       => $stock,
                        'change_type' => ProductStockChangeTypeEnum::SELLER->value
                    ])
                );
            }

            DB::commit();

            return $product;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

    }


}
