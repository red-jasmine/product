<?php


return [
    'actions' => [
        'create' => RedJasmine\Product\Actions\Products\ProductCreateAction::class,
        'update' => RedJasmine\Product\Actions\Products\ProductUpdateAction::class,
        'modify' => RedJasmine\Product\Actions\Products\ProductModifyAction::class,
    ],

    'pipelines' => [
        'create' => [],
        'update' => [],
        'modify' => [],
    ],

];
