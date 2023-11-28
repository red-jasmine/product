<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_property_values', function (Blueprint $table) {

            $table->unsignedBigInteger('vid')->primary()->comment('属性值ID');
            $table->unsignedBigInteger('pid')->comment('属性ID');

            $table->string('name')->comment('名称');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('group_name')->nullable()->comment('分租');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->json('extends')->nullable()->comment('扩展字段');

            $table->string('creator_type', 20)->nullable()->comment('创建者类型');
            $table->unsignedBigInteger('creator_uid')->nullable()->comment('创建者UID');

            $table->string('updater_type', 20)->nullable()->comment('更新者类型');
            $table->unsignedBigInteger('updater_uid')->nullable()->comment('更新者UID');


            $table->timestamps();
            $table->comment('商品-属性值表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_property_values');
    }
};
