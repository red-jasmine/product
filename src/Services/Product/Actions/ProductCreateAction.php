<?php

namespace RedJasmine\Product\Services\Product\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Services\Product\Data\ProductData;
use RedJasmine\Product\Services\Product\Data\ProductSkuData;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\BasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Product\Services\Product\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorCombinerInterface;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\Actions\ResourceCreateAction;

/**
 * @property ProductService $service
 * @property Product        $model
 * @property ProductData    $data
 */
class ProductCreateAction extends ResourceCreateAction
{

    public function __construct(protected PropertyFormatter $propertyFormatter)
    {
    }


    protected ?bool $hasDatabaseTransactions = true;


    protected ?Validator $validator = null;

    /**
     * @return ValidatorCombinerInterface[]|array
     */
    protected function getValidatorCombiner() : array
    {
        return [
            BasicValidator::class,
            PropsValidator::class
        ];
    }

    public function initValidator() : Validator
    {
        // 创建验证器
        $validator = \Illuminate\Support\Facades\Validator::make($this->data->toArray(), [], [], []);
        // 验证管理器
        foreach ($this->getValidatorCombiner() as $validatorCombiner) {
            $validatorCombiner = app($validatorCombiner);
            if ($validatorCombiner instanceof ActionAwareValidatorCombiner) {
                $validatorCombiner->setAction($this);
            }
            if ($validatorCombiner instanceof ValidatorAwareValidatorCombiner) {
                $validatorCombiner->setValidator($validator);
            }
            $validator->addRules($validatorCombiner->rules());
            $validator->setCustomMessages($validatorCombiner->messages());
            $validator->addCustomAttributes($validatorCombiner->attributes());
        }

        return $validator;
    }


    protected function resolveModel() : void
    {
        if ($this->key) {
            $query       = $this->service::getModel()::query();
            $this->model = $this->service->callQueryCallbacks($query)->findOrFail($this->key);
        } else {
            $product = app($this->getModel());
            $product->setRelation('info', new ProductInfo());
            $product->setRelation('skus', collect([]));
            $this->model = $product;
        }
    }

    protected function init($data) : Data
    {
        return $this->conversionData($data);
    }

    protected function validate() : array
    {
        $validator  = $this->initValidator();
        $this->data = $this->conversionData($validator->safe()->all());
        return $this->data->toArray();
    }


    protected function generateId(Model $model) : void
    {
        if ($model->exists === false) {
            $model->{$model->getKeyName()} = $this->service::buildID();
        }
    }

    protected function fill(array $data) : ?Model
    {
        $this->fillProduct($this->model, $this->data);
        $this->fillSkus($this->model, $this->data);
        return $this->model;
    }


    public function fillProduct(Product $product, ProductData $productData) : void
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

    protected function fillSkus(Product $product, ProductData $productData) : void
    {
        // 当前的所有 SKU
        $product->setRelation('skus', $product->skus->keyBy('properties'));

        // 获取数据库中所有的SKU
        $allSku = [];
        if ($product->exists === true) {
            /**
             * @var Collection|array|Product[] $all
             */
            $allSku = $product->skus()->withTrashed()->get()->keyBy('properties');
        }


        if ($product->is_multiple_spec === false) {
            $skuData                 = new ProductSkuData();
            $skuData->properties     = '';
            $skuData->propertiesName = null;
            $sku                     = $allSku[$skuData->properties] ?? $product->skus[$skuData->properties] ?? new ProductSku();

            $data   = $productData->toArray();
            $data   = Arr::only($data, [ 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price' ]);
            $fields = [ 'status', 'image', 'barcode', 'outer_id', 'stock', 'price', 'market_price', 'cost_price', 'sales' ];
            foreach ($fields as $field) {
                $skuData->{$field} = $data[$field] ?? ($sku->{$field} ?? $product->{$field});
            }

            $this->fillSku($sku, $skuData);
            $product->skus[$skuData->properties] = $sku;
        }

        // 如果是多规格
        if ($product->is_multiple_spec === true) {

            $productData->skus?->each(function ($skuData) use ($product, $allSku) {
                /**
                 * @var $skuData ProductSkuData
                 */
                $skuData->properties = $this->propertyFormatter->formatString($skuData->properties);
                $sku                 = $allSku[$skuData->properties] ?? $product->skus[$skuData->properties] ?? new ProductSku();
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
    }

    public function fillSku(ProductSku $sku, ProductSkuData $productSkuData) : void
    {
        $sku->properties      = $productSkuData->properties;
        $sku->properties_name = $productSkuData->propertiesName;
        $sku->image           = $productSkuData->image;
        $sku->barcode         = $productSkuData->barcode;
        $sku->outer_id        = $productSkuData->outerId;
        $sku->properties      = $productSkuData->properties;
        $sku->price           = $productSkuData->price;
        $sku->stock           = $productSkuData->stock;
        $sku->market_price    = $productSkuData->marketPrice;
        $sku->cost_price      = $productSkuData->costPrice;
        $sku->status          = $productSkuData->status ?? ProductStatusEnum::ON_SALE;


    }


    public function handle() : Model
    {
        $product = $this->model;
        $this->service->linkageTime($product);
        $this->generateId($product);

        $product->skus->each(function (ProductSku $sku) {
            $this->generateId($sku);
            $sku->creator    = $this->service->getOperator();
            $sku->deleted_at = null;
        });
        // 统计规格的值
        $this->service->productCountFields($product);
        $product->skus()->saveMany($product->skus);
        $product->info()->save($product->info);
        $this->model->push();
        return $product;
    }


}
