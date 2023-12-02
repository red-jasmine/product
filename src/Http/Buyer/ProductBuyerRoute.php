<?php

namespace RedJasmine\Product\Http\Buyer;

use Illuminate\Support\Facades\Route;
use RedJasmine\Product\Http\Buyer\Controllers\Api\BrandController;
use RedJasmine\Product\Http\Buyer\Controllers\Api\CategoryController;
use RedJasmine\Product\Http\Buyer\Controllers\Api\ProductController;
use RedJasmine\Product\Http\Buyer\Controllers\Api\SellerCategoryController;

class ProductBuyerRoute
{
    public static function web() : void
    {
    }

    public static function api() : void
    {
        Route::group(
            [
                'prefix'    => 'product',
                'namespace' => 'RedJasmine\Product\Http\Buyer\Controllers\Api'
            ],
            function () {
                Route::get('products/{id}', [ ProductController::class, 'show' ])->name('product.buyer.products.show');
                Route::get('products', [ ProductController::class, 'index' ])->name('product.buyer.products.index');

                Route::get('brands/{id}', [ BrandController::class, 'show' ])->name('product.buyer.brand.show');
                Route::get('brands', [ BrandController::class, 'index' ])->name('product.buyer.brand.index');

                Route::get('categories/{id}', [ CategoryController::class, 'show' ])->name('product.buyer.categories.show');
                Route::get('categories', [ CategoryController::class, 'index' ])->name('product.buyer.categories.index');

                Route::get('seller-categories/{id}', [ SellerCategoryController::class, 'show' ])->name('product.buyer.seller-categories.show');
                Route::get('seller-categories', [ SellerCategoryController::class, 'index' ])->name('product.buyer.seller-categories.index');

            });

    }
}
