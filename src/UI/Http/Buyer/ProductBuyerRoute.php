<?php

namespace RedJasmine\Product\UI\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Buyer\Api\Controllers\BrandController;

class ProductBuyerRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::get('brands', [ BrandController::class, 'index' ])->name('buyer.product.brands.index');
            Route::get('brands/{brand}', [ BrandController::class, 'show' ])->name('buyer.product.brands.show');


        });
    }
}
