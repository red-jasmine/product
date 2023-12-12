<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 20)->comment('所属者类型');
            $table->unsignedBigInteger('owner_uid')->comment('所属者UID');

            $table->string('change_type', 20)->comment('更变类型');
            $table->string('change_detail')->nullable()->comment('变更明细');

            $table->unsignedBigInteger('product_id')->comment('SPU ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');

            $table->unsignedBigInteger('before_stock')->comment('变更前');
            $table->bigInteger('change_stock')->comment('变更值');
            $table->unsignedBigInteger('result_stock')->comment('结果库存');

            $table->string('channel_type')->nullable()->comment('渠道类型');
            $table->unsignedBigInteger('channel_id')->nullable()->comment('渠道ID');

            $table->string('creator_type', 20)->nullable()->comment('创建者类型');
            $table->unsignedBigInteger('creator_uid')->nullable()->comment('创建者UID');


            $table->timestamps();
            $table->comment('商品-库存-记录');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stock_logs');
    }
};
