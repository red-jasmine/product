<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;


return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') . 'product_brands', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('english_name')->nullable()->comment('英文名称');
            $table->string('initial', 10)->nullable()->comment('首字母');
            $table->string('logo')->nullable()->comment('标志');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_show')->default(true)->comment('是否展示');
            $table->string('status', 32)->comment(BrandStatusEnum::comments('状态'));
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-品牌');
            $table->index('parent_id','idx_parent');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') .'product_brands');
    }
};
