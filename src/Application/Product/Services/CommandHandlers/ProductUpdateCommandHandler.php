<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\StockInitCommand;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Property\PropertyFormatter;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;

class ProductUpdateCommandHandler extends ProductCommand
{

    public function __construct(
        protected BrandQueryService $brandQueryService,
        public StockCommandService  $stockCommandService,
    )
    {
        $this->stockCommandService->setOperator($this->getOperator());
        parent::__construct();

    }

    public function handle(ProductUpdateCommand $command)
    {
        try {
            DB::beginTransaction();
            /**
             * @var $product Product
             */
            $product = $this->getService()->getRepository()->find($command->id);
            $this->fillProduct($product, $command);

            $product->skus()->withTrashed()->get();
            $product->skus->each(function ($sku) {
                $sku->deleted_at = $sku->deleted_at ?? now();
                $sku->status     = ProductStatusEnum::DELETED;
            });

            switch ($command->isMultipleSpec) {
                case true: // 多规格

                    $propertyFormatter = new PropertyFormatter();
                    // 获取规格信息 TODO 使用服务
                    $saleProps = $propertyFormatter->formatArray($command->saleProps->toArray());

                    // 规格验证 TODO
                    $product->info->sale_props = $saleProps;

                    // 可选  后续考虑 TODO
                    //$defaultSku             = $this->defaultSku($product);
                    //$defaultSku->deleted_at = now();
                    //$product->addSku($defaultSku);

                    $command->skus?->each(function ($skuData) use ($product) {
                        $sku = $product->skus->where('properties', $skuData->properties)->first() ?? new ProductSku(); // TODO 获取实体
                        if (!$sku->exists) {
                            $sku->id = $this->getService()->buildId();
                        }
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
                persistence: fn() => $this->getService()->getRepository()->update($product)
            );


            // 修改库存 把 删除的库存设置为 0
            $skuCommand = $command->skus?->keyBy('properties');

            foreach ($product->skus as $sku) {
                $stock = $skuCommand[$sku->properties]?->stock ?? $command->stock;
                if ($sku->deleted_at) {
                    $stock = 0;
                }
                $this->stockCommandService->reset(StockInitCommand::from(
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
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


    }

}
