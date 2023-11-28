<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_seller_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('类目ID');
            $table->string('owner_type', 20)->comment('所属者类型');
            $table->string('owner_uid', 64)->comment('所属者UID');
            $table->string('owner_nickname', 64)->comment('所属者昵称');
            $table->string('name')->comment('类目名称');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级类目');
            $table->string('group_name')->nullable()->comment('分组');
            $table->string('image')->nullable()->comment('图片');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('is_leaf')->default(0)->comment('是否叶子类目');
            $table->string('status')->comment('状态');
            $table->json('extends')->nullable()->comment('扩展字段');



            $table->string('creator_type', 20)->nullable()->comment('创建者类型');
            $table->unsignedBigInteger('creator_uid')->nullable()->comment('创建者UID');

            $table->string('updater_type', 20)->nullable()->comment('更新者类型');
            $table->unsignedBigInteger('updater_uid')->nullable()->comment('更新者UID');


            $table->timestamps();
            $table->comment('商品-卖家分类表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_seller_categories');
    }
};
