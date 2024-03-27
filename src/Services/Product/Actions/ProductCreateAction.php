<?php

namespace RedJasmine\Product\Services\Product\Actions;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Services\Product\Data\ProductData;
use RedJasmine\Product\Services\Product\Events\ProductCreatedEvent;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\Validators\BasicValidator;
use RedJasmine\Product\Services\Product\Validators\PropsValidator;
use RedJasmine\Product\Services\Property\PropertyFormatter;
use RedJasmine\Support\Foundation\Service\Actions\CreateAction;

/**
 * @property ProductService $service
 * @property Product        $model
 * @property ProductData    $data
 */
class ProductCreateAction extends CreateAction
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
            // TODO 转换 关联关系获取
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
        $product->skus()->saveMany($product->skus);
        $this->service->productCountFields($product);

        $product->info()->save($product->info);
        $this->model->push();
        return $product;
    }


    protected function after($handleResult) : mixed
    {
        ProductCreatedEvent::dispatch($handleResult);
        return $handleResult;
    }


}
