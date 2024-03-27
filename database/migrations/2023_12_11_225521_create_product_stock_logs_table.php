<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('owner');

            $table->string('change_type', 32)->comment('更变类型');
            $table->string('change_detail')->nullable()->comment('变更明细');

            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');

            $table->unsignedBigInteger('before_stock')->comment('变更前');
            $table->bigInteger('change_stock')->comment('变更值');
            $table->unsignedBigInteger('result_stock')->comment('结果库存');

            $table->morphs('channel');

            $table->unsignedTinyInteger('is_lock')->default(0)->comment('是否操作锁定');

            $table->nullableMorphs('creator');
            $table->timestamps();
            $table->comment('商品-库存-记录');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stock_logs');
    }
};
