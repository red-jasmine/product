<?php

namespace RedJasmine\Product\Services\Product\Actions;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Foundation\Service\Actions\DeleteAction;

/**
 * @property Product $model
 */
class ProductDeleteAction extends DeleteAction
{

    protected ?bool $hasDatabaseTransactions = true;

    public function handle() : ?bool
    {
        $product = $this->model;
        $product->info->delete();
        $product->skus()->delete();
        return $product->delete();
    }


}
