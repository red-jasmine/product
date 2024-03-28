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
class ProductCreateAction extends AbstractProductStoreAction
{


    public function execute($data) : Model
    {
        $this->data = $data;
        return $this->store();
    }





    protected function after($handleResult) : mixed
    {
        ProductCreatedEvent::dispatch($handleResult);
        return $handleResult;
    }


}
