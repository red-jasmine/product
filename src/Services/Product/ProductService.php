<?php

namespace RedJasmine\Product\Services\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use RedJasmine\Product\Services\Product\Enums\ProductStatusEnum;
use RedJasmine\Product\Models\Product;
use RedJasmine\Product\Services\Product\Data\ProductData;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Helpers\ID\Snowflake;
use Spatie\QueryBuilder\AllowedFilter;
use Throwable;

/**
 * @see Actions\ProductCreateAction::execute()
 * @method  Product create(ProductData|array $data)
 * @see Actions\ProductUpdateAction::execute()
 * @method  Product update(int $id, ProductData|array $data)
 */
class ProductService extends ResourceService
{

    protected static string $modelClass = Product::class;

    protected static string $dataClass = ProductData::class;

    public static bool $autoModelWithOwner = true;
    // 服务配置
    protected static ?string $serviceConfigKey = 'red-jasmine.product.services.product';


    protected function actions() : array
    {
        return [
            'create' => [
                'class' => Actions\ProductCreateAction::class,
            ],
            'update' => Actions\ProductUpdateAction::class,
            'query'  => [
                'class'    => Actions\ProductQueryAction::class,
                'filters'  => [
                    AllowedFilter::exact('id'),
                    AllowedFilter::exact('owner_type'),
                    AllowedFilter::exact('owner_id'),
                    AllowedFilter::exact('product_type'),
                    AllowedFilter::exact('shipping_type'),
                    AllowedFilter::partial('title'),
                    AllowedFilter::exact('outer_id'),
                    AllowedFilter::exact('is_multiple_spec'),
                    AllowedFilter::exact('status'),
                    AllowedFilter::exact('brand_id'),
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::exact('seller_category_id'),
                ],
                'includes' => [
                    'info', 'skus', 'skus.info', 'brand', 'category', 'sellerCategory', 'series'
                ],
                'fields'   => [],
                'sorts'    => [],
            ],
            'delete' => Actions\ProductDeleteAction::class,
        ];

    }

    public function stock() : ProductStockService
    {
        return app(ProductStockService::class);
    }

    /**
     * 最大库存
     */
    public const MAX_QUANTITY = 9999999999;


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




    public function find(int $id) : Product
    {
        return $this->query()->findOrFail($id);
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
