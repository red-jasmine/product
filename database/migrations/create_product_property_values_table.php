<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix','jasmine_') . 'product_property_values', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性值ID');
            $table->unsignedBigInteger('pid')->comment('属性ID');
            $table->unsignedBigInteger('group_id')->default(0)->comment('属性组ID');
            $table->string('name', 64)->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->comment(PropertyStatusEnum::comments('状态'));
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-属性值表');
            $table->index('pid', 'idx_property');
            $table->index('group_id', 'idx_group');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix','jasmine_') . 'product_property_values');
    }
};
