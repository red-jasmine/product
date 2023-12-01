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


                Route::group([
                                 'prefix' => 'product-categories'
                             ], function () {
                    Route::get('{id}', [ ProductCategoryController::class, 'show' ]);
                    Route::get('/', [ ProductCategoryController::class, 'index' ]);
                });


                Route::apiResource('product-seller-categories', ProductSellerCategoryController::class);


                Route::group([
                                 'prefix' => 'brands'
                             ], function () {
                    Route::get('{id}', [ BrandController::class, 'show' ]);
                    Route::get('/', [ BrandController::class, 'index' ]);
                });
            });

    }

}
