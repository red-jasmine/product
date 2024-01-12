<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Omnipay\Common\Http\Exception;
use RedJasmine\Product\Actions\Products\ProductCreateAction;
use RedJasmine\Product\Actions\Products\ProductUpdateAction;
use RedJasmine\Product\DataTransferObjects\ProductDTO;
use RedJasmine\Product\Enums\Product\ProductStatusEnum;
use RedJasmine\Product\Enums\Stock\ProductStockChangeTypeEnum;
use RedJasmine\Product\Exceptions\ProductStockException;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Models\ProductInfo;
use RedJasmine\Product\Services\Product\Builder\ProductBuilder;
use RedJasmine\Product\Services\Product\Stock\ProductStockService;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Throwable;

/**
 * @see ProductCreateAction::execute()
 * @method static Product create(ProductDTO $productDTO)
 * @see ProductUpdateAction::execute()
 * @method static Product update(int $id, ProductDTO $productDTO)
 */
class ProductService extends Service
{

    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 9999999999;
    protected static ?string       $actionsConfigKey = 'red-jasmine.product.actions';
    public string                  $model            = Product::class;
    protected ?ProductStockService $stockService     = null;
    protected ?ProductBuilder      $productBuilder   = null;

    public function queries() : ProductQuery
    {
        return new ProductQuery($this);
    }

    public function productBuilder() : ProductBuilder
    {
        if ($this->productBuilder) {
            return $this->productBuilder;
        }

        $this->productBuilder = new ProductBuilder();
        $this->productBuilder
            ->setOwner($this->getOwner())
            ->setOperator($this->getOperator());
        return $this->productBuilder;
    }


    /**
     * 联动设置时间
     *
     * @param Product $product
     *
     * @return void
     */
    public function linkageTime(Product $product) : void
    {

        if (!$product->isDirty('status')) {
            return;
        }
        switch ($product->status) {
            case ProductStatusEnum::ON_SALE: // 在售
                $product->on_sale_time = now();
                break;
            case ProductStatusEnum::OUT_OF_STOCK: // 缺货
                $product->sold_out_time = now();
                break;
            case ProductStatusEnum::SOLD_OUT: // 售停
                $product->sold_out_time = now();
                break;
            case ProductStatusEnum::OFF_SHELF: // 下架
                $product->off_sale_time = now();
                break;
            case ProductStatusEnum::DELETED:
            case ProductStatusEnum::PRE_SALE:

                break;
            case ProductStatusEnum::FORBID:// 强制下架
                $product->on_sale_time  = null;
                $product->sold_out_time = null;
                $product->off_sale_time = $product->off_sale_time ?? now();
                break;

        }
    }

    /**
     * 复用字段
     *
     * @param Product $product
     * @param Product $sku
     *
     * @return void
     */
    public function copyProductAttributeToSku(Product $product, Product $sku) : void
    {
        $sku->owner_type         = $product->owner_type;
        $sku->owner_id           = $product->owner_id;
        $sku->is_multiple_spec   = BoolIntEnum::NO;
        $sku->is_sku             = BoolIntEnum::YES;
        $sku->spu_id             = $product->id;
        $sku->title              = $product->title;
        $sku->product_type       = $product->product_type;
        $sku->shipping_type      = $product->shipping_type;
        $sku->title              = $product->title;
        $sku->category_id        = $product->category_id;
        $sku->seller_category_id = $product->seller_category_id;
        $sku->freight_payer      = $product->freight_payer;
        $sku->postage_id         = $product->postage_id;
        $sku->sub_stock          = $product->sub_stock;
        $sku->delivery_time      = $product->delivery_time;
        $sku->vip                = (int)($product->vip ?? 0);
        $sku->points             = (int)($product->points ?? 0);
        $sku->status             = $product->status;
        $sku->deleted_at         = null;
    }


    public function find(int $id) : Product
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * @return Builder|Product
     */
    public function query() : Builder
    {
        return Product::query()->productable();
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function generateID() : int
    {
        return Snowflake::getInstance()->nextId();
    }

    /**
     * 删除
     *
     * @param int $id
     *
     * @return true
     * @throws AbstractException
     * @throws Throwable
     */
    public function delete(int $id) : true
    {
        try {
            DB::beginTransaction();
            $product = $this->find($id);
            $product->info->delete();
            $product->skus()->delete();
            $product->delete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;
    }

    /**
     *  强制删除
     *
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function forceDelete(int $id) : bool
    {
        try {
            DB::beginTransaction();
            /**
             * @var Product $product
             */
            $product = $this->query()->onlyTrashed()->find($id);
            $product->info()->onlyTrashed()->forceDelete();
            $product->skus()->onlyTrashed()->forceDelete();
            $product->forceDelete();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;


    }

    /**
     * 恢复
     *
     * @param int $id
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function restore(int $id) : bool
    {
        try {
            DB::beginTransaction();
            /**
             * @var Product $product
             */
            $product = $this->query()->onlyTrashed()->find($id);
            $product->info()->onlyTrashed()->restore();
            $product->skus()
                    ->onlyTrashed()
                    ->where('status', '<>', ProductStatusEnum::DELETED->value)
                    ->restore();
            $product->restore();
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (ModelNotFoundException $modelNotFoundException) {
            DB::rollBack();
            throw  $modelNotFoundException;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return true;


    }

    /**
     * 设状态
     *
     * @param int               $id
     * @param ProductStatusEnum $productStatus
     *
     * @return bool
     */
    public function updateStatus(int $id, ProductStatusEnum $productStatus) : bool
    {
        $product                = $this->find($id);
        $product->status        = $productStatus;
        $product->modified_time = now();
        $product->updater       = $this->getOperator();
        $product->save();

        return true;
    }


}
