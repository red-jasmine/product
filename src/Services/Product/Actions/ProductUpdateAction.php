<?php

namespace RedJasmine\Product\Services\Product\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Services\Product\Enums\ProductStatusEnum;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Services\Product\Data\ProductData;
use RedJasmine\Product\Services\Product\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Services\Product\Events\ProductUpdatedEvent;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\Validators\ActionAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\BasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Product\Services\Product\Validators\ValidatorAwareValidatorCombiner;
use RedJasmine\Product\Services\Product\Validators\ValidatorCombinerInterface;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Foundation\Service\Actions\UpdateAction;
use Throwable;

/**
 * @property ProductService $service
 * @property Product        $model
 * @property ProductData    $data
 */
class ProductUpdateAction extends UpdateAction
{

    public function __construct(protected PropertyFormatter $propertyFormatter)
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
            $product = app($this->getModelClass());
            $product->setRelation('info', new ProductInfo());
            $product->setRelation('skus', collect([]));
            $this->model = $product;

        }
    }


    protected function fill(array $data) : ?Model
    {
        app(ProductFill::class)->fill($this->model, $this->data, $data);
        return $this->model;
    }


    /**
     * @return Model
     * @throws Throwable
     */
    public function handle() : Model
    {
        $product = $this->model;
        $this->service->linkageTime($product);

        if ($product->isDirty() || $product->info->isDirty()) {
            $product->updater       = $this->service->getOperator();
            $product->modified_time = now();
        }
        $this->service->linkageTime($product);
        $this->updateSkus($product);
        if ($product->is_multiple_spec === false) {
            $product->info->sale_props = null;
        }
        // 统计规格的值
        $product->skus()->saveMany($product->skus->values());
        $this->service->productCountFields($product);
        $product->save();
        $product->info->save();
        return $product;
    }

    /**
     * @param Product $product
     *
     * @return void
     * @throws Throwable
     */
    protected function updateSkus(Product $product) : void
    {

        // 如果是改变了规格类型的情况下 那么就 重置库存
        if ($product->isDirty('is_multiple_spec')) {
            $product->lock_stock = 0;
        }
        // 获取数据库中所有的SKU
        /**
         * @var Collection|array|ProductSku[] $all
         */
        $all = $product->skus()->withTrashed()->get()->keyBy('properties');


        // 设置 正常的SKU

        $product->skus->each(function (ProductSku $sku, $index) use ($product) {
            $this->generateId($sku);
            if (blank($sku->properties)) {
                $sku->id = $product->id;
                if ($product->is_multiple_spec === true) {
                    $sku->deleted_at = now();
                }
            }
            if ($sku->exists === false) {
                $sku->creator = $this->service->getOperator();
            }
            if ($sku->isDirty()) {
                $product->modified_time = now();
                $sku->updater           = $this->service->getOperator();
            }

            if ($sku->exists === true) {
                $this->service->stock()->setStock($sku->id, $sku->stock, ProductStockChangeTypeEnum::SELLER);
            }
        });


        $all->each(function (ProductSku $sku, $properties) use ($product) {
            if ($this->isCloseSku($sku, $product)) {
                $this->closeSku($sku);
            }
        });
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
        $sku->status     = ProductStatusEnum::DELETED;
        $sku->deleted_at = $sku->deleted_at ?? now();
        $sku->save();
    }

    protected function after($handleResult) : mixed
    {
        ProductUpdatedEvent::dispatch($handleResult);
        return parent::after($handleResult); // TODO: Change the autogenerated stub
    }


}
