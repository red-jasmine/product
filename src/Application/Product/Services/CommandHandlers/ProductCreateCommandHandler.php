<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\Sku;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\StockInitCommand;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Property\PropertyFormatter;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Support\Application\CommandHandler;

class ProductCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected BrandQueryService $brandQueryService,
        public StockCommandService  $stockCommandService,
    )
    {
        $this->stockCommandService->setOperator($this->getOperator());
        parent::__construct();

    }

    // 需要组合 品牌服务、分类服务、卖家分类服务、属性服务

    public function handle(ProductCreateCommand $command) : void
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

            // 多规格区别处理
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
            $skuCommand = $command->skus->keyBy('properties');
            foreach ($product->skus as $sku) {
                if ($sku->deleted_at) {
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
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

    }


    protected function defaultSku(Product $product) : ProductSku
    {
        $sku                  = new ProductSku();
        $sku->id              = $product->id;
        $sku->properties      = '000000:000000;';
        $sku->properties_name = '';
        $sku->price           = $product->price;
        $sku->cost_price      = $product->cost_price;
        $sku->market_price    = $product->market_price;
        $sku->safety_stock    = $product->safety_stock;
        $sku->image           = $product->image;
        $sku->barcode         = $product->barcode;
        $sku->outer_id        = $product->outer_id;
        $sku->status          = ProductStatusEnum::ON_SALE;
        return $sku;
    }

    protected function fillProduct(Product $product, ProductCreateCommand $command) : void
    {
        $product->owner              = $command->owner;
        $product->product_type       = $command->productType;
        $product->shipping_type      = $command->shippingType;
        $product->title              = $command->title;
        $product->image              = $command->image;
        $product->barcode            = $command->barcode;
        $product->outer_id           = $command->outerId;
        $product->is_multiple_spec   = $command->isMultipleSpec;
        $product->sort               = $command->sort;
        $product->unit               = $command->unit;
        $product->status             = $command->status;
        $product->price              = $command->price;
        $product->market_price       = $command->marketPrice;
        $product->cost_price         = $command->costPrice;
        $product->brand_id           = $command->brandId;
        $product->category_id        = $command->categoryId;
        $product->seller_category_id = $command->sellerCategoryId;
        $product->freight_payer      = $command->freightPayer;
        $product->postage_id         = $command->postageId;
        $product->min_limit          = $command->minLimit;
        $product->max_limit          = $command->maxLimit;
        $product->step_limit         = $command->stepLimit;
        $product->sub_stock          = $command->subStock;
        $product->delivery_time      = $command->deliveryTime;
        $product->vip                = $command->vip;
        $product->points             = $command->points;
        $product->is_hot             = $command->isHot;
        $product->is_new             = $command->isNew;
        $product->is_best            = $command->isBest;
        $product->is_benefit         = $command->isBenefit;
        $product->promise_services   = $command->promiseServices;
        $product->safety_stock       = $command->safetyStock;


        $product->info->id          = $product->id;
        $product->info->videos      = $command->videos;
        $product->info->images      = $command->images;
        $product->info->keywords    = $command->keywords;
        $product->info->description = $command->description;
        $product->info->detail      = $command->detail;
        $product->info->images      = $command->images;
        $product->info->videos      = $command->videos;
        $product->info->weight      = $command->weight;
        $product->info->width       = $command->width;
        $product->info->height      = $command->height;
        $product->info->length      = $command->length;
        $product->info->size        = $command->size;
        $product->info->basic_props = $command->basicProps;
        $product->info->sale_props  = $command->saleProps;
        $product->info->remarks     = $command->remarks;
        $product->info->tools       = $command->tools;
        $product->info->expands     = $command->expands;
        $product->info->basic_props = $command->basicProps;
        $product->info->sale_props  = $command->saleProps;


    }


    protected function fillSku(ProductSku $sku, Sku $data) : void
    {
        $sku->properties   = $data->properties;
        $sku->image        = $data->image;
        $sku->barcode      = $data->barcode;
        $sku->outer_id     = $data->outerId;
        $sku->properties   = $data->properties;
        $sku->price        = $data->price;
        $sku->safety_stock = $data->safetyStock;
        $sku->market_price = $data->marketPrice;
        $sku->cost_price   = $data->costPrice;
        $sku->status       = $data->status;

    }


}
