<?php

namespace RedJasmine\Product\Services\Product\Stock;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductChannelStock;
use RedJasmine\Product\Models\ProductSku;
use RedJasmine\Product\Models\ProductStockLog;
use RedJasmine\Product\Services\Product\Data\StockChannelData;
use RedJasmine\Product\Services\Product\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;
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
        // TODO 需要加上 渠道库存
        return new ProductStockChannel($this);
    }

    /**
     * 初始化
     *
     * @param ProductSku $sku
     * @param int        $stock
     * @param bool       $onlyLog
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function initStock(ProductSku $sku, int $stock, bool $onlyLog = false) : ?ProductStockLog
    {
        // 设置库存采用悲观锁
        if (bccomp($stock, 0, 0) < 0) {
            throw new ProductStockException('库存 数量必须大于等于 0');
        }
        if ($sku->exists === true) {
            throw new ProductStockException('库存 数量必须大于等于 0');
        }
        try {
            DB::beginTransaction();
            if ($onlyLog === false) {
                $stockUpdate = DB::raw("stock + $stock");
                Product::where('id', $sku->product_id)->update([ 'stock' => $stockUpdate ]);
            }
            $productStockLog = $this->log($sku, $stock, 0, ProductStockChangeTypeEnum::INIT);
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
     * 全量设置库存
     *
     * @param ProductSku                 $sku
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
    public function setStock(ProductSku $sku, int $stock, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SELLER, string $changeDetail = null) : ?ProductStockLog
    {
        // 设置库存采用悲观锁
        if (bccomp($stock, 0, 0) < 0) {
            throw new ProductStockException('库存 数量必须大于等于 0');
        }
        try {
            DB::beginTransaction();
            $sku = $this->getSKU($sku->id);
            // 如果没有改变库存 那么不进行操作
            if (bccomp($sku->stock, $stock, 0) === 0) {
                DB::commit();
                return null;
            }
            // 最小要求库存 = 当前库存  - 渠道占用库存
            $minStock = $sku->channel_stock; // 渠道占用
            $quantity = bcsub($stock, $sku->stock, 0);
            // 如果调整后的库存 小于渠道占用
            if (bccomp($stock, $minStock, 0) < 0) {
                throw new ProductStockException('渠道库存占用:' . $sku->channel_stock, 0, [ 'channel_stock' => $sku->channel_stock ]);
            }
            $stockUpdate = DB::raw("stock + $quantity");
            ProductSku::withTrashed()->where('id', $sku->id)->update([ 'stock' => $stockUpdate ]);
            Product::withTrashed()->where('id', $sku->product_id)->update([ 'stock' => $stockUpdate ]);
            // 添加更变日志
            $productStockLog = $this->log($sku, $quantity, 0, $changeTypeEnum, null, $changeDetail);
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
    public function add(ProductSku $sku, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SELLER, string $changeDetail = '') : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);

        try {
            DB::beginTransaction();
            ProductSku::where('id', $sku->id)->increment('stock', $quantity);
            Product::where('id', $sku->product_id)->increment('stock', $quantity);
            // 添加更变日志
            $productStockLog = $this->log($sku, +$quantity, 0, $changeTypeEnum, null, $changeDetail);
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
     * 减库存
     *
     * @param ProductSku                 $sku
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelData|null      $channel
     * @param bool                       $lock
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function sub(ProductSku $sku, int $quantity, bool $lock = false, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SALE, ?StockChannelData $channel = null, string $changeDetail = '') : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $lockStock = 0;
            $update    = [ 'stock' => DB::raw("stock - $quantity") ];
            if ($lock) {
                $lockStock            = $quantity;
                $update['lock_stock'] = DB::raw("lock_stock + $quantity");
            }
            $rows = ProductSku::where('id', $sku->id)
                              ->where('stock', '>=', $quantity)
                              ->update($update);
            if ($rows <= 0) {
                throw new ProductStockException('库存不足');
            }
            Product::where('id', $sku->product_id)->update($update);
            // 添加更变日志
            $productStockLog = $this->log($sku, -$quantity, +$lockStock, $changeTypeEnum, null, $changeDetail);
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
     * @param ProductSku                 $sku
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelData|null      $channel
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function lock(ProductSku $sku, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SALE, ?StockChannelData $channel = null, string $changeDetail = '') : ?ProductStockLog
    {
        return $this->sub($sku, $quantity, true, $changeTypeEnum, $channel, true);
    }


    /**
     * 解锁
     *
     * @param ProductSku                 $sku
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelData|null      $channel
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function unlock(ProductSku $sku, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SALE, ?StockChannelData $channel = null, string $changeDetail = '') : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $update['stock']      = DB::raw("stock + $quantity");
            $update['lock_stock'] = DB::raw("lock_stock - $quantity");
            $rows                 = ProductSku::where('id', $sku->id)
                                              ->where('lock_stock', '>=', $quantity)
                                              ->update($update);
            if ($rows <= 0) {
                throw new ProductStockException('锁定库存不足');
            }
            Product::where('id', $sku->product_id)->update($update);
            // 添加更变日志
            $productStockLog = $this->log($sku, +$quantity, -$quantity, $changeTypeEnum, null, $changeDetail);
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
     * @param ProductSku                 $sku
     * @param int                        $quantity
     * @param ProductStockChangeTypeEnum $changeTypeEnum
     * @param StockChannelData|null      $channel
     * @param string                     $changeDetail
     *
     * @return ProductStockLog|null
     * @throws AbstractException
     * @throws ProductStockException
     * @throws Throwable
     */
    public function checkLock(ProductSku $sku, int $quantity, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SALE, ?StockChannelData $channel = null, string $changeDetail = '') : ?ProductStockLog
    {
        $quantity = $this->validateQuantity($quantity);
        try {
            DB::beginTransaction();
            $update['lock_stock'] = DB::raw("lock_stock - $quantity");

            $rows = ProductSku::where('id', $sku->id)
                              ->where('lock_stock', '>=', $quantity)
                              ->update($update);
            if ($rows <= 0) {
                throw new ProductStockException('锁定库存不足');
            }
            Product::where('id', $sku->product_id)->update($update);
            //  添加确认操作
            $productStockLog = $this->log($sku, 0, -$quantity, $changeTypeEnum, null, $changeDetail);
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


    public function log(ProductSku $sku, int $stock, int $lockStock = 0, ProductStockChangeTypeEnum $changeTypeEnum = ProductStockChangeTypeEnum::SELLER, ?StockChannelData $channel = null, string $changeDetail = null) : ?ProductStockLog
    {

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
        $productStockLog->lock_stock = $lockStock;
        $productStockLog->save();
        return $productStockLog;
    }


}
