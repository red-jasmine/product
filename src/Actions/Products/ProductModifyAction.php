<?php

namespace RedJasmine\Product\Actions\Products;

use Illuminate\Support\Facades\DB;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\Models\Product;

class ProductModifyAction extends AbstractProductAction
{
    public function execute(int $id, ProductDTO $productDTO) : Product
    {
        try {
            DB::beginTransaction();
            $product = $this->service->find($id);
            // TODO
            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


        return $product;
    }

}
