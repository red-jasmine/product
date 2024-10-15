<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') . 'product_series_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('series_id')->comment('系列ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->string('name')->comment('名称');
            $table->timestamps();
            $table->unique([ 'series_id', 'product_id' ], 'uk_series_product');
            $table->index('product_id', 'idx_product');
            $table->index('series_id', 'idx_series');
            $table->comment('商品系列-商品关联表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') . 'product_series_products');
    }
};
