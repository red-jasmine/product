<?php

namespace RedJasmine\Product\Services\Product\Stock;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\ProductChannelStock;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\ProductStock;
use RedJasmine\Product\Services\Product\ServiceExtends;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Helpers\ID\Snowflake;

class ProductStockChannel
{

    use ServiceExtends;

    public function __construct(protected ProductService $service, protected ProductStock $stockService)
    {


    }


    /**
     * 创建逻辑库存
     *
     * @param int                   $skuID
     * @param int                   $quantity
     * @param StockChannelInterface $channel
     *
     * @return ProductChannelStock|null
     * @throws AbstractException
     * @throws ProductStockException
     */
    public function create(int $skuID, int $quantity, StockChannelInterface $channel) : ?ProductChannelStock
    {
        $quantity = $this->stockService->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            $sku = $this->stockService->getSKU($skuID);

            // 可用库存  必须大于等于 逻辑库存
            // 可用库存 = 可售库存 - 逻辑库存
            $ableStock = bcsub($sku->stock, $sku->channel_stock, 0);
            if (bccomp($ableStock, $quantity, 0) < 0) {
                throw new ProductStockException('可用库存不足');
            }

            // 创建可用库存
            try {
                $this->stockService->getChannelStock($skuID, $channel);
                throw new ProductStockException('逻辑库存存在 不可重复创建');
            } catch (ModelNotFoundException $modelNotFoundException) {

            }

            $sku->channel_stock = bcadd($sku->channel_stock,$quantity,0);
            $sku->save();

            $productChannelStock = new ProductChannelStock();
            $productChannelStock->id = Snowflake::getInstance()->nextId();
            $productChannelStock->withOwner($sku->getOwner());
            $productChannelStock->sku_id              = $sku->id;
            $productChannelStock->product_id          = $sku->spu_id === 0 ? $sku->id : $sku->spu_id;
            $productChannelStock->channel_type        = $channel->channelType();
            $productChannelStock->channel_id          = $channel->channelID();
            $productChannelStock->channel_stock       = $quantity;
            $productChannelStock->channel_total_stock = $quantity;
            $productChannelStock->save();
            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            throw  $throwable;
        }
        return $productChannelStock;
    }


    public function add()
    {

    }


    public function sub()
    {

    }
}
