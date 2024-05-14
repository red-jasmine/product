<?php

namespace RedJasmine\Product\UI\Http\Seller;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\BrandController;
use RedJasmine\Product\UI\Http\Seller\Api\Controllers\CategoryController;

class ProductSellerRoute
{

    public static function api() : void
    {
        Route::group([ 'prefix' => 'product' ], function () {

            Route::get('brands', [ BrandController::class, 'index' ])->name('seller.product.brands.index');
            Route::get('brands/{brand}', [ BrandController::class, 'show' ])->name('seller.product.brands.show');


            Route::get('categories', [ CategoryController::class, 'tree' ])->name('seller.product.categories.tree');
            Route::get('categories/{category}', [ CategoryController::class, 'show' ])->name('seller.product.categories.show');


        });
    }
}