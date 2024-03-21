<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('SKU ID');
            $table->unsignedBigInteger('product_id')->default(0)->comment('商品ID');
            $table->string('properties')->nullable()->comment('规格属性值字符串');
            $table->string('properties_name', 60)->nullable()->comment('规格属性名称');
            // SKU 信息
            $table->decimal('price', 10, 2, true)->default(0)->comment('销售价');
            $table->decimal('market_price', 10, 2, true)->nullable()->comment('市场价');
            $table->decimal('cost_price', 10, 2, true)->nullable()->comment('成本价');
            $table->unsignedBigInteger('sales')->default(0)->comment('销量');

            // 库存
            $table->unsignedBigInteger('stock')->default(0)->comment('库存');
            $table->unsignedBigInteger('virtual_stock')->default(0)->comment('虚拟库存');
            $table->unsignedBigInteger('channel_stock')->default(0)->comment('渠道库存');
            $table->unsignedBigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->unsignedBigInteger('safety_stock')->default(0)->comment('安全库存');
            // 信息
            $table->string('image')->nullable()->comment('主图');
            $table->string('barcode', 32)->nullable()->comment('条形码');
            $table->string('outer_id', 20)->nullable()->comment('商家编码');
            // 状态
            $table->string('status')->comment('状态');
            // 操作
            $table->timestamp('modified_time')->nullable()->comment('修改时间');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品SKU表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_skus');
    }
};
