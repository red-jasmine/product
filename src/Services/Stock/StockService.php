<?php

namespace RedJasmine\Product\Services\Stock;

use Exception;
use Illuminate\Support\Str;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Product\Services\Stock\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Services\Stock\Data\StockActionData;
use RedJasmine\Support\Foundation\Service\ResourceService;


/**
 * @method init(StockActionData|array $data, bool $onlyLog = false)
 * @method reset(StockActionData|array $data)
 * @method add(StockActionData|array $data)
 * @method sub(StockActionData|array $data)
 * @method lock(StockActionData|array $data)
 * @method unlock(StockActionData|array $data)
 * @method confirm(StockActionData|array $data)
 */
class StockService extends ResourceService
{

    protected static string $modelClass = Product::class;

    protected static string $dataClass = StockActionData::class;
    // 服务配置
    protected static ?string $serviceConfigKey = 'red-jasmine.product.services.stock';


    protected function actions() : array
    {
        return [
            'init'    => Actions\StockInitAction::class,
            'reset'   => Actions\StockResetAction::class,
            'add'     => Actions\StockAddAction::class,
            'sub'     => Actions\StockSubAction::class,
            'lock'    => Actions\StockLockAction::class,
            'unlock'  => Actions\StockUnlockAction::class,
            'confirm' => Actions\StockConfirmAction::class,

            // TODO 库存列表 、库存记录、渠道库存
        ];

    }

    /**
     * 获取库存单位
     *
     * @param int $skuID
     *
     * @return ProductSku
     */
    public function getSKU(int $skuID) : ProductSku
    {
        return ProductSku::lockForUpdate()
                         ->withTrashed()
                         ->select([ 'id', 'product_id', 'stock', 'lock_stock', 'channel_stock', ])
                         ->findOrFail($skuID);


    }


    /**
     * @param int                        $productId
     * @param int                        $skuId
     * @param int                        $stock
     * @param int                        $lockStock
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelData|null      $channel
     * @param string|null                $changeDetail
     *
     * @return ProductStockLog|null
     * @throws Exception
     */
    public function log(int $productId, int $skuId, int $stock, int $lockStock = 0, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SELLER, string $changeDetail = null, ?StockChannelData $channel = null) : ?ProductStockLog
    {
        $productStockLog                = new ProductStockLog();
        $productStockLog->id            = static::buildID();
        $productStockLog->creator       = $this->getOperator();
        $productStockLog->sku_id        = $skuId;
        $productStockLog->product_id    = $productId;
        $productStockLog->stock         = $stock;
        $productStockLog->lock_stock    = $lockStock;
        $productStockLog->change_type   = $changeTypeEnum;
        $productStockLog->change_detail = Str::limit((string)$changeDetail, 200, '');
        if ($channel) {
            $productStockLog->channel_type = $channel->type;
            $productStockLog->channel_id   = $channel->id;
        }
        $productStockLog->save();
        return $productStockLog;
    }


}
