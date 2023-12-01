<?php

namespace RedJasmine\Product\Http\Seller\Controllers;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\Http\Seller\Controllers\Api\BrandController;
use RedJasmine\Product\Http\Seller\Controllers\Api\ProductSellerCategoryController;
use RedJasmine\Product\Http\Seller\Controllers\Api\ProductCategoryController;

class ProductSellerRoute
{

    public static function web() : void
    {
    }

    public static function api() : void
    {
        Route::group(
            [
                'prefix'    => 'product',
                'namespace' => 'RedJasmine\Product\Http\Seller\Controllers\Api'
            ],
            function () {


                Route::get('product-categories/{id}', [ ProductCategoryController::class, 'show' ])->name('product.seller.product-categories.show');
                Route::get('product-categories', [ ProductCategoryController::class, 'index' ])->name('product.seller.product-categories.index');

                Route::get('brands/{id}', [ BrandController::class, 'show' ])->name('product.seller.brand.show');
                Route::get('brands', [ BrandController::class, 'index' ])->name('product.seller.brand.index');


                Route::apiResource('product-seller-categories', ProductSellerCategoryController::class)->names('product.seller.product-seller-categories');


            });

    }

}
