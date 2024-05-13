<?php

namespace RedJasmine\Product\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Buyer\Api\Controllers\BrandController;

class ProductBuyerApiRoute
{

    public static function routes() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::get('brands', [ BrandController::class, 'index' ])->name('product.buyer.brands.index');
            Route::get('brands/{id}', [ BrandController::class, 'show' ])->name('product.buyer.brands.show');


        });
    }
}
