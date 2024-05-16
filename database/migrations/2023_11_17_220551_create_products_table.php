<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->primary()->comment('ID');
            // 卖家信息
            $table->morphs('owner');
            $table->string('title', 60)->comment('标题');
            // 商品类型 普通、虚拟、拍卖、酒店订单、外卖
            $table->string('product_type', 20)->comment('商品类型');
            // 发货类型 物流 虚拟 卡密 在线服务 线下服务等 主要处理 发货流程 运费计算等 决定收货时间
            $table->string('shipping_type', 20)->comment('发货类型');
            // 发货类
            $table->unsignedInteger('delivery_time')->default(0)->comment('发货时间:小时');

            $table->unsignedTinyInteger('is_multiple_spec')->default(0)->comment('多规格');
            // 基础信息
            $table->string('image')->nullable()->comment('主图');
            $table->string('barcode', 32)->nullable()->comment('条形码');
            $table->string('outer_id', 20)->nullable()->comment('商家编码');
            // 状态相关
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->string('status')->comment('状态');
            // 类目信息
            $table->unsignedBigInteger('brand_id')->nullable()->comment('品牌');
            $table->unsignedBigInteger('category_id')->nullable()->comment('类目');
            $table->unsignedBigInteger('seller_category_id')->nullable()->comment('卖家分类');
            // 运费
            $table->unsignedTinyInteger('freight_payer')->default(0)->comment('运费承担方');
            $table->unsignedBigInteger('postage_id')->nullable()->comment('运费模板ID');
            // 库存
            $table->unsignedTinyInteger('sub_stock')->default(0)->comment('减库存方式');

            $table->decimal('price', 10)->default(0)->comment('销售价');
            $table->decimal('market_price', 10)->nullable()->comment('市场价');
            $table->decimal('cost_price', 10)->nullable()->comment('成本价');
            $table->unsignedBigInteger('stock')->default(0)->comment('库存');
            $table->unsignedBigInteger('channel_stock')->default(0)->comment('渠道库存');
            $table->unsignedBigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->unsignedBigInteger('safety_stock')->default(0)->comment('安全库存');

            //统计项
            $table->unsignedBigInteger('views')->default(0)->comment('浏览量');
            $table->unsignedBigInteger('sales')->default(0)->comment('销量');
            // 用户类
            $table->unsignedTinyInteger('vip')->default(0)->comment('VIP');
            $table->unsignedInteger('points')->default(0)->comment('积分');
            // 用户限购类
            $table->unsignedBigInteger('min')->default(1)->comment('起购量');
            $table->unsignedBigInteger('max')->default(0)->comment('限购量');
            $table->unsignedBigInteger('multiple')->default(1)->comment('购买倍数');
            // 展现
            $table->unsignedTinyInteger('is_hot')->default(0)->comment('热销');
            $table->unsignedTinyInteger('is_new')->default(0)->comment('新品');
            $table->unsignedTinyInteger('is_best')->default(0)->comment('精品');
            $table->unsignedTinyInteger('is_benefit')->default(0)->comment('特惠');
            // 时间
            $table->timestamp('on_sale_time')->nullable()->comment('上架时间');
            $table->timestamp('sold_out_time')->nullable()->comment('售停时间');
            $table->timestamp('off_sale_time')->nullable()->comment('下架时间');

            // 供应商

            // 承诺服务


            // 操作人
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->timestamp('modified_time')->nullable()->comment('修改时间');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');

            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品表');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
