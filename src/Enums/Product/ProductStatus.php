<?php

namespace RedJasmine\Product\Enums\Product;

enum ProductStatus: string
{

    case ON_SALE = 'on_sale'; // 在售

    case OUT_OF_STOCK = 'out_of_stock'; // 缺货

    case SOLD_OUT = 'sold_out'; // 售停

    case IN_STOCK = 'in_stock'; // 仓库中

    case OFF_SHELF = 'off_shelf'; // 下架

    case PRE_SALE = 'pre_sale'; // 预售

    case FORCED_OFF_SHELF = 'forced_off_shelf'; // 强制下架

}
