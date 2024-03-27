<?php

namespace RedJasmine\Product\Services\Product;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Services\Product\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\ProductChannelStock;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Product\Services\Product\Data\StockChannelData;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Throwable;

class ProductStockService
{
    /**
     * 渠道渠道库存
     * @return ProductStockChannel
     */
    public function channel() : ProductStockChannel
    {
        return new ProductStockChannel($this);
    }


    /**
     * 全量设置库存
     *
     * @param int                        $skuID
     * @param int                        $stock
     *
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param string|null                $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function setStock(int $skuID, int $stock, ProductStockChangeTypeEnum $changeTypeEnum, string $changeDetail = null) : ?ProductStockLog
    {

        if (bccomp($stock, 0, 0) < 0) {
            throw new ProductStockException('库存 数量必须大于等于 0');
        }
        try {
            DB::beginTransaction();
            $sku         = $this->getSKU($skuID);
            $beforeStock = $sku->stock;
            // 如果没有改变库存 那么不进行操作
            if (bccomp($sku->stock, $stock, 0) === 0) {
                DB::commit();
                return null;
            }
            // 可售库存
            $saleableStock = bcsub($sku->stock, $sku->channel_stock, 0);

            // 如果为 小于0 为 减库存  大于0 为加库存
            $quantity = bcsub($stock, $beforeStock, 0);

            // 如果减少的库存 大于了 可售库存
            if (bcadd($saleableStock, $quantity, 0) < 0) {
                throw new ProductStockException('渠道库存占用:' . $sku->channel_stock, 0, [ 'channel_stock' => $sku->channel_stock ]);
            }

            // 更新库存
            $sku->stock = bcadd($sku->stock, $quantity, 0);
            $sku->save();
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $quantity, null, false, $changeDetail);
            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $productStockLog;

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
     * 获取渠道库存
     *
     * @param int              $skuID
     * @param StockChannelData $channel
     *
     * @return ProductChannelStock
     */
    public function getChannelStock(int $skuID, StockChannelData $channel) : ProductChannelStock
    {
        return ProductChannelStock::lockForUpdate()
                                  ->channel($channel)
                                  ->where('sku_id', $skuID)
                                  ->firstOrFail();
    }


    /**
     * 验证库存
     *
     * @param int $quantity
     *
     * @return int
     * @throws ProductStockException
     */
    public function validateQuantity(int $quantity) : int
    {
        // 核心操作 $quantity 都为 正整数
        if (bccomp($quantity, 0, 0) <= 0) {
            throw new ProductStockException('操作库存 数量必须大于 0');
        }
        return $quantity;
    }

    /**
     * @param ProductSku                 $sku
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function add(ProductSku $sku, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, string $changeDetail = '') : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            ProductSku::where('id', $sku->id)->increment('stock', $quantity);
            Product::where('id', $sku->product_id)->increment('stock', $quantity);
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $quantity, null, false, $changeDetail);
            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            throw  $throwable;
        }
    }


    /**
     *
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     * @param bool                       $lock
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function sub(ProductSku $sku, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null, bool $lock = false, string $changeDetail = '') : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $rows = ProductSku::where('id', $sku->id)->where('stock', '>=', $quantity)->decrement('stock', $quantity);
            if ($rows <= 0) {
                throw new ProductStockException('库存不足');
            }
            Product::where('id', $sku->product_id)->decrement('stock', $quantity);
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $quantity, null, false, $changeDetail);
            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }


    /**
     * 锁定库存
     *
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function lock(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null, string $changeDetail = '') : ?ProductStockLog
    {
        return $this->sub($skuID, $quantity, $changeTypeEnum, $channel, true);
    }


    /**
     * 解锁
     *
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function unlock(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null, string $changeDetail = '') : ?ProductStockLog
    {

        $quantity = $this->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            $sku = $this->getSKU($skuID);
            // 如果是普通扣减 还是渠道扣减
            $beforeStock = $sku->stock;

            // 判断 总锁定库存 是否充足
            if (bccomp(bcsub($sku->lock_stock, $quantity, 0), 0, 0) < 0) {
                throw new ProductStockException('锁定库存不足');
            }

            if ($channel instanceof StockChannelInterface) {
                // 渠道扣减
                $channelStock = $this->getChannelStock($skuID, $channel);
                // 判断渠道锁定库存是否充足
                if (bccomp(bcsub($channelStock->channel_lock_stock, $quantity, 0), 0, 0) < 0) {
                    throw new ProductStockException('渠道锁定库存不足');
                }
            }

            // 实物库存操作
            $sku->stock      = bcadd($sku->stock, $quantity, 0); // 反向操作
            $sku->lock_stock = bcsub($sku->lock_stock, $quantity, 0);
            // 渠道库存操作
            if ($channel instanceof StockChannelInterface) {
                // 总渠道库存
                $sku->channel_stock               = bcadd($sku->channel_stock, $quantity, 0);
                $channelStock->channel_stock      = bcadd($channelStock->channel_stock, $quantity, 0);
                $channelStock->channel_lock_stock = bcsub($channelStock->channel_lock_stock, $quantity, 0);
                $channelStock->save();
            }

            $sku->save();
            // 添加更变日志
            $productStockLog = $this->log($sku, $changeTypeEnum, $beforeStock, $quantity, $sku->stock, $channel, true, $changeDetail);
            DB::commit();
            return $productStockLog;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


    }


    /**
     * 扣减锁定
     *
     * @param int                        $skuID
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelInterface|null $channel
     * @param string                     $changeDetail
     *
     * @return bool
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function checkLock(int $skuID, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum, ?StockChannelInterface $channel = null, string $changeDetail = '') : bool
    {
        $quantity = $this->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            $sku = $this->getSKU($skuID);

            // 判断 总锁定库存 必须大于等于 确认库存
            if (bccomp(bcsub($sku->lock_stock, $quantity, 0), 0, 0) < 0) {
                throw new ProductStockException('锁定库存不足');
            }
            if ($channel instanceof StockChannelInterface) {
                // 渠道扣减
                $channelStock = $this->getChannelStock($skuID, $channel);
                if (bccomp(bcsub($channelStock->channel_lock_stock, $quantity, 0), 0, 0) < 0) {
                    throw new ProductStockException('渠道锁定库存不足');
                }

            }

            // 总锁定库存 = 总锁定库存 - 数量
            $sku->lock_stock = bcsub($sku->lock_stock, $quantity, 0);

            // 渠道库存操作
            if ($channel instanceof StockChannelInterface) {

                // 渠道锁定库存 = 渠道锁定库存 - 数量
                $channelStock->channel_lock_stock = bcsub($channelStock->channel_lock_stock, $quantity, 0);
                $channelStock->save();
            }
            $sku->save();
            // 添加更变日志
            DB::commit();
            return true;
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }

    }


    public function log(ProductSku $sku, ProductStockChangeTypeEnum $changeTypeEnum, int $stock, ?StockChannelData $channel = null, bool $lock = false, string $changeDetail = null) : ?ProductStockLog
    {
        if (bccomp($stock, 0, 0) === 0) {
            return null;
        }

        $productStockLog     = new ProductStockLog();
        $productStockLog->id = Snowflake::getInstance()->nextId();

        // TODO
        //$productStockLog->creator       = $this->getOperator();
        $productStockLog->sku_id        = $sku->id;
        $productStockLog->product_id    = $sku->product_id;
        $productStockLog->stock         = $stock;
        $productStockLog->change_type   = $changeTypeEnum;
        $productStockLog->change_detail = Str::limit((string)$changeDetail, 200, '');
        if ($channel) {
            $productStockLog->channel_type = $channel->type;
            $productStockLog->channel_id   = $channel->id;
        }
        $productStockLog->is_lock = (int)$lock;
        $productStockLog->save();
        return $productStockLog;
    }


}
