<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            // 图片+ 详情
            $table->string('desc')->nullable()->comment('商品描述');
            $table->longText('web_detail')->nullable()->comment('电脑详情');
            $table->longText('wap_detail')->nullable()->comment('手机详情');
            $table->json('images')->nullable()->comment('图片集');
            $table->json('videos')->nullable()->comment('视频集');
            // 物品基本信息
            $table->string('weight')->nullable()->comment('重:kg');
            $table->string('width')->nullable()->comment('宽:m');
            $table->string('height')->nullable()->comment('高:m');
            $table->string('length')->nullable()->comment('长:m');
            $table->string('size')->nullable()->comment('体积:m³');
            // 属性
            $table->json('basic_props')->nullable()->comment('基本属性');
            $table->json('sale_props')->nullable()->comment('销售属性');
            $table->string('remarks')->nullable()->comment('备注');
            $table->json('tools')->nullable()->comment('工具参数');
            $table->json('extends')->nullable()->comment('扩展参数');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-附加信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_infos');
    }
};
