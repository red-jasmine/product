<?php

namespace RedJasmine\Product\Services\Property\Actions\Names;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Models\ProductProperty;
use RedJasmine\Product\Services\Property\Data\PropertyData;
use RedJasmine\Product\Services\Property\Events\ProductPropertyCreatedEvent;
use RedJasmine\Product\Services\Property\Pipelines\ModelWithOperator;
use RedJasmine\Product\Services\Property\PropertyService;
use RedJasmine\Support\Foundation\Service\Actions;
use Throwable;

/**
 * @property PropertyService $service
 */
class PropertyUpdateAction extends Actions
{


    protected static array $commonPipes = [
        ModelWithOperator::class,
    ];

    public string|int|null   $id   = null;
    public PropertyData|null $data = null;

    /**
     * @var ProductProperty|null
     */
    public Model|null $model = null;

    /**
     * @var string
     */
    protected string $modelClass = ProductProperty::class;


    /**
     * @param int          $id
     * @param PropertyData $data
     *
     * @return ProductProperty
     * @throws Throwable
     */
    public function execute(int $id, PropertyData $data) : ProductProperty
    {
        $this->id   = $id;
        $this->data = $data;
        // 初始化管道
        $this->initPipelines($this);
        try {
            $this->beginDatabaseTransaction();
            // 初始化
            $this->pipelines->init(fn() => $this->init());
            // 验证
            $this->pipelines->validate(fn() => $this->validate());
            // 填充
            $this->pipelines->fill(fn() => $this->fill());
            // 处理
            $this->pipelines->handle(fn() => $this->handle());
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        $this->pipelines->event(fn() => $this->event());
        // 返回值
        return $this->model;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function init() : void
    {
        // 创建实例 或者重数据查询唯一
        $this->model = ProductProperty::findOrFail($this->id);


    }

    protected function validate() : void
    {
        // TODO 验证数据
        $this->data->toArray();


    }

    protected function fill() : void
    {
        $this->model->name     = $this->data->name;
        $this->model->group_id = $this->data->groupId;
        $this->model->sort     = $this->data->sort;
        $this->model->extends  = $this->data->extends;
        $this->model->status   = $this->data->status;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle() : void
    {
        $this->model->save();
    }


    protected function event() : void
    {
        ProductPropertyCreatedEvent::dispatch($this->model);
    }


}
