<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_channel_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('owner');
            $table->morphs('channel');
            $table->unsignedBigInteger('product_id')->comment('SPU ID');
            $table->unsignedBigInteger('sku_id')->comment('SKU ID');
            $table->unsignedBigInteger('channel_total_stock')->default(0)->comment('渠道总库存');
            $table->unsignedBigInteger('channel_stock')->default(0)->comment('渠道库存');
            $table->unsignedBigInteger('channel_lock_stock')->default(0)->comment('渠道锁定库存');

            $table->timestamps();
            $table->comment('渠道库存');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_channel_stocks');
    }
};
