<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('name')->comment('名称');
            $table->string('logo')->nullable()->comment('标志');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->json('extends')->nullable()->comment('扩展字段');
            $table->timestamps();
            $table->comment('商品-品牌');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('brands');
    }
};
