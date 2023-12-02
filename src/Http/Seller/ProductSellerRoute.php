<?php

namespace RedJasmine\Product\Http\Seller;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\Http\Seller\Controllers\Api\BrandController;
use RedJasmine\Product\Http\Seller\Controllers\Api\ProductController;
use RedJasmine\Product\Http\Seller\Controllers\Api\SellerCategoryController;
use RedJasmine\Product\Http\Seller\Controllers\Api\CategoryController;

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


                Route::get('categories/{id}', [ CategoryController::class, 'show' ])->name('product.seller.categories.show');
                Route::get('categories', [ CategoryController::class, 'index' ])->name('product.seller.categories.index');

                Route::get('brands/{id}', [ BrandController::class, 'show' ])->name('product.seller.brand.show');
                Route::get('brands', [ BrandController::class, 'index' ])->name('product.seller.brand.index');


                Route::apiResource('seller-categories', SellerCategoryController::class)->names('product.seller.product-seller-categories');
                Route::apiResource('products', ProductController::class)->names('product.seller.products');


            });

    }

}
