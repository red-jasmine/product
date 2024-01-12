<?php

namespace RedJasmine\Product\Services\Product\Stock;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\ProductChannelStock;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\ServiceExtends;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Helpers\ID\Snowflake;

class ProductStockChannel
{

    use ServiceExtends;

    public function __construct(protected ProductStockService $stockService)
    {


    }


    /**
     * 创建渠道库存
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

            // 可用库存  必须大于等于 渠道库存
            // 可用库存 = 可售库存 - 渠道库存
            $ableStock = bcsub($sku->stock, $sku->channel_stock, 0);
            if (bccomp($ableStock, $quantity, 0) < 0) {
                throw new ProductStockException('可用库存不足');
            }

            // 创建可用库存
            try {
                $this->stockService->getChannelStock($skuID, $channel);
                throw new ProductStockException('渠道库存存在 不可重复创建');
            } catch (ModelNotFoundException $modelNotFoundException) {

            }

            $sku->channel_stock = bcadd($sku->channel_stock, $quantity, 0);
            $sku->save();

            $productChannelStock                      = new ProductChannelStock();
            $productChannelStock->id                  = Snowflake::getInstance()->nextId();
            $productChannelStock->owner               = $sku->owner;
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


    /**
     * 追加渠道库存
     *
     * @param int                   $skuID
     * @param int                   $quantity
     * @param StockChannelInterface $channel
     *
     * @return ProductChannelStock
     * @throws AbstractException
     * @throws ProductStockException
     */
    public function add(int $skuID, int $quantity, StockChannelInterface $channel) : ProductChannelStock
    {
        $quantity = $this->stockService->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $sku = $this->stockService->getSKU($skuID);

            // 可用库存  必须大于等于 渠道库存
            // 可用库存 = 可售库存 - 渠道库存
            $ableStock = bcsub($sku->stock, $sku->channel_stock, 0);
            if (bccomp($ableStock, $quantity, 0) < 0) {
                throw new ProductStockException('可用库存不足');
            }

            // 操作渠道库存
            $productChannelStock = $this->stockService->getChannelStock($skuID, $channel);
            $sku->channel_stock  = bcadd($sku->channel_stock, $quantity, 0);
            $sku->save();
            // 操作物理库存
            $productChannelStock->channel_total_stock = bcadd($productChannelStock->channel_total_stock, $quantity, 0);
            $productChannelStock->channel_stock       = bcadd($productChannelStock->channel_stock, $quantity, 0);
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


    /**
     * 减少渠道库存
     *
     * @param int                   $skuID
     * @param int                   $quantity
     * @param StockChannelInterface $channel
     *
     * @return ProductChannelStock
     * @throws AbstractException
     * @throws ProductStockException
     */
    public function sub(int $skuID, int $quantity, StockChannelInterface $channel) : ProductChannelStock
    {
        $quantity = $this->stockService->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $sku = $this->stockService->getSKU($skuID);

            // 验证渠道渠道库存
            $productChannelStock = $this->stockService->getChannelStock($skuID, $channel);
            // 判断渠道库存 的 渠道可售库存 必须大于等级 数量
            if (bccomp($productChannelStock->channel_stock, $quantity, 0) < 0) {
                throw new ProductStockException('渠道可用库存不足');
            }
            // 操作 总渠道库存
            // 渠道库存 = 渠道库存 - 数量
            // 渠道可用库存 = 渠道可用库存 - 数量
            $productChannelStock->channel_total_stock = bcsub($productChannelStock->channel_total_stock, $quantity, 0);
            $productChannelStock->channel_stock       = bcsub($productChannelStock->channel_stock, $quantity, 0);
            $productChannelStock->save();
            //  总渠道库存 =  总渠道库存 - 数量
            $sku->channel_stock = bcsub($sku->channel_stock, $quantity, 0);
            $sku->save();
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
}
