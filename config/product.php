<?php


return [
    //
    'price_decimal_total' => 14,// 价格小数点精度


    'price_decimal_precision' => 6,// 价格小数点精度

    'actions' => [
        'create' => RedJasmine\Product\Actions\Products\ProductCreateAction::class,
        'update' => RedJasmine\Product\Actions\Products\ProductUpdateAction::class,
    ],

    'pipelines'  => [
        'create' => [],
    ],

    // 商品验证器
    'validators' => [

        // 类目验证
        // 卖家分类验证
        // 价格验证
        // 图片验证
        // 视频验证

    ],

];
