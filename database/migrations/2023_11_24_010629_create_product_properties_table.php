<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('pid')->primary()->comment('属性ID');

            $table->string('name')->comment('名称');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->json('extends')->nullable()->comment('扩展字段');

            $table->string('creator_type', 20)->comment('创建者类型');
            $table->string('creator_uid', 64)->comment('创建者ID');
            $table->string('creator_nickname', 64)->nullable()->comment('创建者昵称');

            $table->string('updater_type', 20)->nullable()->comment('更新者类型');
            $table->string('updater_uid', 64)->nullable()->comment('更新者UID');
            $table->string('updater_nickname', 64)->nullable()->comment('更新者昵称');

            $table->timestamps();
            $table->comment('商品-属性表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_properties');
    }
};
