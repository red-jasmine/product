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
            $table->string('owner_type', 20)->comment('所属者类型');
            $table->string('owner_uid', 64)->comment('所属者UID');
            // 商品类型 实物、虚拟、拍卖、酒店订单、外卖
            $table->string('product_type', 20)->nullable()->comment('商品类型');
            // 发货类型 物流 虚拟 卡密 在线服务 线下服务等 主要处理 发货流程 运费计算等 决定收货时间
            $table->string('shipping_type', 20)->nullable()->comment('发货类型');
            // 分类
            $table->unsignedBigInteger('category_id')->default()->comment('类目ID');
            $table->unsignedBigInteger('seller_category_id')->nullable()->comment('卖家分类ID');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('品牌ID');

            $table->string('title', 60)->comment('标题');
            $table->string('image')->nullable()->comment('主图');
            $table->string('barcode', 32)->nullable()->comment('条形码');
            $table->string('outer_id', 20)->nullable()->comment('商家编码');
            $table->string('keywords')->nullable()->comment('商品关键字');
            // 多规格 查询: 商品级 parent_id = 0  规格级 is_sku = 1
            $table->unsignedBigInteger('parent_id')->default(0)->comment('规格父级');
            $table->unsignedTinyInteger('has_skus')->default(0)->comment('含有多规格');
            $table->unsignedTinyInteger('is_sku')->default(1)->comment('是否最小规格');
            // 规格信息
            $table->string('properties')->nullable()->comment('规格属性值');
            $table->string('properties_name')->nullable()->comment('规格属性名称');
            // 价格
            $table->decimal('price', 10, 2, true)->default(0)->comment('销售价');
            $table->decimal('market_price', 10, 2, true)->default(0)->comment('市场价');
            $table->decimal('cost_price', 10, 2, true)->default(0)->comment('成本价');
            // 运费
            $table->unsignedTinyInteger('freight_payer')->default(0)->comment('运费承担方');
            $table->unsignedBigInteger('postage_id')->default(0)->comment('运费模板ID');
            // 下单类
            $table->unsignedBigInteger('min')->default(0)->comment('起购量');
            $table->unsignedBigInteger('max')->default(0)->comment('限购量');
            $table->unsignedBigInteger('multiple')->default(1)->comment('购买倍数');
            // 库存
            $table->unsignedTinyInteger('sub_stock')->default(0)->comment('减库存方式');
            $table->unsignedBigInteger('quantity')->default(999999999)->comment('库存数量');
            $table->unsignedBigInteger('hold_quantity')->default(0)->comment('预扣库存');
            $table->unsignedBigInteger('sold_quantity')->default(0)->comment('销售数量');
            // 发货类
            $table->unsignedInteger('delivery_time')->default(0)->comment('小时内发货');
            // 用户类
            $table->unsignedTinyInteger('vip')->default(0)->comment('VIP');
            $table->unsignedInteger('points')->default(0)->comment('积分');
            // 状态相关
            $table->string('status')->comment('商品状态');
            // 时间
            $table->timestamp('on_sale_time')->nullable()->comment('上架时间');
            $table->timestamp('sold_out_time')->nullable()->comment('售停时间');
            $table->timestamp('off_sale_time')->nullable()->comment('下架时间');
            $table->timestamp('modified_time')->nullable()->comment('修改时间');

            // 操作人
            $table->string('creator_type', 20)->nullable()->comment('创建者类型');
            $table->string('creator_uid', 64)->nullable()->comment('创建者ID');
            $table->string('creator_nickname', 64)->nullable()->comment('创建者昵称');

            $table->string('updater_type', 20)->nullable()->comment('更新者类型');
            $table->string('updater_uid', 64)->nullable()->comment('更新者UID');
            $table->string('updater_nickname', 64)->nullable()->comment('更新者昵称');

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
