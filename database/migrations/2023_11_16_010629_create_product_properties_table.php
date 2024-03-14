<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('pid')->primary()->comment('属性ID');
            $table->unsignedBigInteger('group_id')->nullable()->comment('属性组ID');
            $table->string('name')->comment('名称');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', PropertyStatusEnum::values())->comment(PropertyStatusEnum::comments('状态'));
            $table->json('extends')->nullable()->comment('扩展字段');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');

            $table->timestamps();
            $table->comment('商品-属性表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_properties');
    }
};
