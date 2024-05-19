<?php

namespace RedJasmine\Product\Services\Stock;

use Exception;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductSku;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Product\Services\Stock\Data\StockActionData;
use RedJasmine\Support\Foundation\Service\ResourceService;
use Spatie\QueryBuilder\QueryBuilder;


/**
 * @method init(StockActionData|array $data, bool $onlyLog = false)
 * @method reset(StockActionData|array $data)
 * @method add(StockActionData|array $data)
 * @method sub(StockActionData|array $data)
 * @method lock(StockActionData|array $data)
 * @method unlock(StockActionData|array $data)
 * @method confirm(StockActionData|array $data)
 * @method  QueryBuilder logsQuery(bool $isRequest = false)
 */
class StockService extends ResourceService
{

    protected static string $modelClass = Product::class;

    protected static string $dataClass = StockActionData::class;
    // 服务配置
    protected static ?string $serviceConfigKey = 'red-jasmine.product.services.stock';

    protected array $actions = [];

    protected function actions() : array
    {
        return [
            'query'     => Actions\StockQueryAction::class,
            'logsQuery' => Actions\StockLogsQueryAction::class,
            'init'      => Actions\StockInitAction::class,
            'reset'     => Actions\StockResetAction::class,
            'add'       => Actions\StockAddAction::class,
            'sub'       => Actions\StockSubAction::class,
            'lock'      => Actions\StockLockAction::class,
            'unlock'    => Actions\StockUnlockAction::class,
            'confirm'   => Actions\StockConfirmAction::class,

            // TODO 库存列表 、库存记录、渠道库存
        ];

    }

    /**
     * 获取库存单位
     *
     * @param int $skuID
     *
     * @return \RedJasmine\Product\Domain\Product\Models\ProductSku
     */
    public function getSKU(int $skuID) : ProductSku
    {
        return ProductSku::lockForUpdate()
                         ->withTrashed()
                         ->select([ 'id', 'product_id', 'stock', 'lock_stock', 'channel_stock', ])
                         ->findOrFail($skuID);


    }


    /**
     * @param \RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum       $type
     * @param int                                                                      $productId
     * @param int                                                                      $skuId
     * @param int                                                                      $stock
     * @param int                                                                      $lockStock
     * @param \RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum $changeTypeEnum
     * @param string|null                                                              $changeDetail
     *
     * @param StockChannelData|null                                                    $channel
     * @param array|null                                                               $extends
     *
     * @return ProductStockLog|null
     * @throws Exception
     */
    public function log(ProductStockTypeEnum $type, int $productId, int $skuId, int $stock, int $lockStock = 0, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SELLER, string $changeDetail = null, ?StockChannelData $channel = null, array $extends = null) : ?ProductStockLog
    {
        $productStockLog                = new ProductStockLog();
        $productStockLog->id            = static::buildID();
        $productStockLog->creator       = $this->getOperator();
        $productStockLog->type          = $type;
        $productStockLog->sku_id        = $skuId;
        $productStockLog->product_id    = $productId;
        $productStockLog->stock         = $stock;
        $productStockLog->lock_stock    = $lockStock;
        $productStockLog->change_type   = $changeTypeEnum;
        $productStockLog->change_detail = Str::limit((string)$changeDetail, 200, '');
        $productStockLog->channel_type  = $channel?->type;
        $productStockLog->channel_id    = $channel?->id;
        $productStockLog->extends       = $extends;
        $productStockLog->save();
        return $productStockLog;
    }


}
