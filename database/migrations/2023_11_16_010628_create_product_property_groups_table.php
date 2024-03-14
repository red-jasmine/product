<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Services\Property\Enums\PropertyStatusEnum;

return new class extends Migration {
    public function up() : void
    {

        Schema::create('product_property_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性组ID');
            $table->string('name')->comment('名称');
            $table->enum('status', PropertyStatusEnum::values())->comment(PropertyStatusEnum::comments('状态'));
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->comment('商品-属性分组表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_property_groups');
    }
};
