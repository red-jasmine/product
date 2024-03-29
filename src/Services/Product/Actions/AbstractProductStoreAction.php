<?php

namespace RedJasmine\Product\Services\Product\Actions;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Enums\ProductStatusEnum;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Services\Product\Data\ProductData;
use RedJasmine\Product\Services\Product\Data\ProductPropData;
use RedJasmine\Product\Services\Product\Data\ProductSkuData;
use RedJasmine\Product\Services\Product\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\Validators\BasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;
use Throwable;

/**
 * @property ProductService $service
 */
abstract class AbstractProductStoreAction extends ResourceAction
{

    public function __construct(public PropertyFormatter $propertyFormatter)
    {
        parent::__construct();
    }

    protected ?bool $hasDatabaseTransactions = true;

    protected static array $globalValidatorCombiners = [
        BasicValidator::class,
        PropsValidator::class
    ];

    protected function resolveModel() : void
    {
        if ($this->key) {
            $query       = $this->service::getModelClass()::query();
            $this->model = $this->service->callQueryCallbacks($query)->findOrFail($this->key);
        } else {
            // TODO 转换 关联关系获取
            $product = app($this->getModelClass());
            $product->setRelation('info', new ProductInfo());
            $product->setRelation('skus', collect([]));
            $this->model = $product;
        }
    }

    /**
     * @return Model
     * @throws Throwable
     */
    public function handle() : Model
    {
        $product = $this->model;
        return $this->storeProduct($product);
    }


    protected function fill(array $data) : ?Model
    {
        $this->handleFill($this->model, $this->data, $data);
        return $this->model;
    }


    /**
     * @param Product $product
     *
     * @return Product
     * @throws Exception|Throwable
     */
    protected function storeProduct(Product $product) : Product
    {
        // 生成ID
        $this->generateId($product);
        // 如果是已存在的 同时修改了
        $product->creator = $product->creator ?? $this->service->getOperator();
        if ($product->exists === true) {
            // 使用外部变量 TODO
            $skusAll = $product->skusAll;
            unset($product->skusAll);
            // 防止判断出错
            if ($product->isDirty() || $product->info->isDirty()) {
                $product->updater       = $this->service->getOperator();
                $product->modified_time = now();
            }
            $product->skusAll = $skusAll;
        }
        if ($product->is_multiple_spec === false) {
            $product->info->sale_props = [];
        }
        $this->service->linkageTime($product);
        $product->skus->each(function (ProductSku $sku, $index) use ($product) {
            $this->generateId($sku);
            $sku->product_id = $product->id;
            $sku->deleted_at = null;
            // 如果是还没有创建的
            $sku->creator = $sku->creator ?? $this->service->getOperator();
            if ($sku->exists === true && $sku->isDirty()) {
                $sku->modified_time = now();
                $sku->updater       = $this->service->getOperator();
            }
            if (blank($sku->properties)) {
                $sku->id = $product->id;
                if ($product->is_multiple_spec === true) {
                    $sku->deleted_at = now();
                }
            }
            $product->skusAll[$sku->properties] = $sku;
        });
        // 数据库中的SKU
        $product->skusAll->each(function (ProductSku $sku, $properties) use ($product) {
            //$sku = $product->skus[$properties] ?? $sku;
            // 对于不需要的SKU 进行关闭 和 清空库存
            if ($this->isCloseSku($sku, $product)) {
                $this->closeSku($sku);
            }
        });

        // 合并产品部分参数
        $this->productCountFields($product);
        // 修改库存

        $product->skusAll->each(function (ProductSku $sku, $properties) use ($product) {
            if ($sku->exists === false) {
                // 新建的SKU
                $onlyLog = ($product->exists === false);
                $this->service->stock()->init([ 'change_type' => 'init', 'sku_id' => $sku->id, 'product_id' => $sku->product_id, 'stock' => $sku->stock ], $onlyLog);
            } else {
                // 老的SKU
                $this->service->stock()->reset([ 'sku_id' => $sku->id, 'product_id' => $sku->product_id, 'stock' => $sku->stock ]);
                unset($sku->stock); // 不对 stock 进行更新
            }
        });
        // 持久化操作
        $product->skus()->saveMany($product->skusAll->values());
        unset($product->skusAll);
        $product->info()->save($product->info);
        $product->push();
        return $product;

    }

    public function productCountFields(Product $product) : void
    {
        $product->price        = $product->skus->where('deleted_at', null)->min('price');
        $product->cost_price   = $product->skus->where('deleted_at', null)->min('cost_price');
        $product->market_price = $product->skus->where('deleted_at', null)->min('market_price');
        $product->safety_stock = $product->skus->where('deleted_at', null)->max('safety_stock');
        if ($product->exists === false) {
            $product->stock = $product->skusAll->sum('stock');
        }
    }

    protected function isCloseSku(ProductSku $sku, Product $product) : bool
    {
        if ($sku->status === ProductStatusEnum::DELETED) {
            return false;
        }
        if ($product->is_multiple_spec === true) {
            return !in_array($sku->properties, $product->skus->pluck('properties')->toArray(), true);
        }
        // 如果是单规格
        return filled($sku->properties);
    }

    protected function closeSku(ProductSku $sku) : void
    {
        // TODO 如果存在占用库存 那么就不能进行 删除
        $sku->stock      = 0; // 如果删除了 那么需要设置库存为0
        $sku->status     = ProductStatusEnum::DELETED;
        $sku->deleted_at = $sku->deleted_at ?? now();

    }


    /**
     * @param Product     $product
     * @param ProductData $productData
     * @param array       $data
     *
     * @return Product
     * @throws ProductPropertyException
     */
    public function handleFill(Product $product, ProductData $productData, array $data = []) : Product
    {
        $productData->skus             = collect(ProductSkuData::collect($data['skus'] ?? []));
        $productData->info->basicProps = collect(ProductPropData::collect($data['info']['basic_props'] ?? []));
        $productData->info->saleProps  = collect(ProductPropData::collect($data['info']['sale_props'] ?? []));
        $this->fillProduct($product, $productData);
        $this->fillSkus($product, $productData);
        return $product;
    }

    protected function fillProduct(Product $product, ProductData $productData) : void
    {

        $product->owner              = $productData->owner;
        $product->product_type       = $productData->productType;
        $product->shipping_type      = $productData->shippingType;
        $product->title              = $productData->title;
        $product->image              = $productData->image;
        $product->barcode            = $productData->barcode;
        $product->outer_id           = $productData->outerId;
        $product->is_multiple_spec   = $productData->isMultipleSpec;
        $product->sort               = $productData->sort;
        $product->status             = $productData->status;
        $product->price              = $productData->price;
        $product->market_price       = $productData->marketPrice;
        $product->cost_price         = $productData->costPrice;
        $product->brand_id           = $productData->brandId;
        $product->category_id        = $productData->categoryId;
        $product->seller_category_id = $productData->sellerCategoryId;
        $product->freight_payer      = $productData->freightPayer;
        $product->postage_id         = $productData->postageId;
        $product->min                = $productData->min;
        $product->max                = $productData->max;
        $product->multiple           = $productData->multiple;
        $product->sub_stock          = $productData->subStock;
        $product->delivery_time      = $productData->deliveryTime;
        $product->vip                = $productData->vip;
        $product->points             = $productData->points;
        $product->is_hot             = $productData->isHot;
        $product->is_new             = $productData->isNew;
        $product->is_best            = $productData->isBest;
        $product->is_benefit         = $productData->isBenefit;

        $product->info->description = $productData->info?->description;
        $product->info->detail      = $productData->info?->detail;
        $product->info->images      = $productData->info?->images;
        $product->info->videos      = $productData->info?->videos;
        $product->info->weight      = $productData->info?->weight;
        $product->info->width       = $productData->info?->width;
        $product->info->height      = $productData->info?->height;
        $product->info->length      = $productData->info?->length;
        $product->info->size        = $productData->info?->size;
        $product->info->basic_props = $productData->info?->basicProps;
        $product->info->sale_props  = $productData->info?->saleProps;
        $product->info->remarks     = $productData->info?->remarks;
        $product->info->tools       = $productData->info?->tools;
        $product->info->extends     = $productData->info?->extends;


    }

    /**
     * @param Product     $product
     * @param ProductData $productData
     *
     * @return void
     * @throws ProductPropertyException
     */
    protected function fillSkus(Product $product, ProductData $productData) : void
    {
        // 当前的所有 SKU
        $product->setRelation('skus', $product->skus->keyBy('properties'));


        // 获取数据库中所有的SKU 临时使用的
        $product->skusAll = collect([]);
        if ($product->exists === true) {
            /**
             * @var Collection|array|Product[] $all
             */
            $product->skusAll = $product->skus()->withTrashed()->get()->keyBy('properties');
        }

        // 如果是多规格
        if ($product->is_multiple_spec === true) {

            $productData->skus?->each(function ($skuData) use ($product) {
                /**
                 * @var $skuData ProductSkuData
                 */
                $skuData->properties = $this->propertyFormatter->formatString($skuData->properties);
                $sku                 = $product->skusAll[$skuData->properties] ?? $product->skus[$skuData->properties] ?? new ProductSku();
                $this->fillSku($sku, $skuData);
                $product->skus[$skuData->properties] = $sku;
            });
            if ($productData->skus) {
                $newSkus = collect($productData->skus)->pluck('properties')->values()->toArray();
                $product->skus->pluck('properties')->each(function ($properties) use ($product, $newSkus) {
                    if (!in_array($properties, $newSkus, true)) {
                        unset($product->skus[$properties]);
                    }
                });
            }
        }


        $skuData                 = new ProductSkuData();
        $skuData->properties     = '';
        $skuData->propertiesName = null;
        /**
         * @var ProductSku $basicSKU
         */
        $basicSKU             = $product->skusAll[$skuData->properties] ?? $product->skus[$skuData->properties] ?? new ProductSku();
        $basicSKU->status     = ProductStatusEnum::DELETED;
        $basicSKU->stock      = 0;
        $basicSKU->deleted_at = now();
        // 添加一个默认规格 规格ID 和商品ID 一样
        // 如果 是单规格
        if ($product->is_multiple_spec === false) {
            $data                   = $productData->toArray();
            $fields                 = [ 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price', ];
            $data                   = Arr::only($data, $fields);
            $data['properties']     = '';
            $data['propertiesName'] = null;
            $skuData                = ProductSkuData::from($data);
            $this->fillSku($basicSKU, $skuData);
            $basicSKU->status     = ProductStatusEnum::ON_SALE;
            $basicSKU->deleted_at = null;
        }
        $product->skus[$skuData->properties] = $basicSKU;
    }

    protected function fillSku(ProductSku $sku, ProductSkuData $productSkuData) : void
    {
        $sku->properties      = $productSkuData->properties;
        $sku->properties_name = $productSkuData->propertiesName;
        $sku->image           = $productSkuData->image;
        $sku->barcode         = $productSkuData->barcode;
        $sku->outer_id        = $productSkuData->outerId;
        $sku->properties      = $productSkuData->properties;
        $sku->price           = $productSkuData->price;
        $sku->stock           = $productSkuData->stock;
        $sku->safety_stock    = $productSkuData->safetyStock;
        $sku->market_price    = $productSkuData->marketPrice;
        $sku->cost_price      = $productSkuData->costPrice;
        $sku->status          = $productSkuData->status ?? ProductStatusEnum::ON_SALE;

    }

}
