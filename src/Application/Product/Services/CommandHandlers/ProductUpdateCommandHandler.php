<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use Throwable;

/**
 * @method  ProductCommandService getService()
 */
class ProductUpdateCommandHandler extends ProductCommand
{


    public function handle(ProductUpdateCommand $command)
    {
        try {
            DB::beginTransaction();
            /**
             * @var $product Product
             */
            $product = $this->getService()->getRepository()->find($command->id);
            $product->setRelation('skus', $product->skus()->withTrashed()->get());

            $product->skus->each(function ($sku) {
                $sku->setDeleted();
            });

            $this->handleCore($product, $command);


            $this->execute(
                execute: null,
                persistence: fn() => $this->getService()->getRepository()->update($product)
            );

            $this->handleStock($product, $command);
            DB::commit();

            return $product;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


    }


}