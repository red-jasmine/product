<?php

namespace RedJasmine\Product\Services\Brand;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Product\Exceptions\BrandException;
use RedJasmine\Product\Models\Brand;
use RedJasmine\Product\Services\Brand\Actions\BrandCreateAction;
use RedJasmine\Product\Services\Brand\Actions\BrandDeleteAction;
use RedJasmine\Product\Services\Brand\Actions\BrandQueryAction;
use RedJasmine\Product\Services\Brand\Actions\BrandUpdateAction;
use RedJasmine\Product\Services\Brand\Data\BrandData;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Rules\NotZeroExistsRule;

/**
 * @method  BrandQueryAction query()
 * @method  Brand create(BrandData|array $data)
 * @method  Brand update(int $id, BrandData|array $data)
 * @method  bool delete(int $id)
 */
class BrandService extends ResourceService
{

    use HasQueryBuilder;

    protected static string $model = Brand::class;

    protected static string $data = BrandData::class;

    /**
     * 操作管道配置前缀
     * @var string|null
     */
    protected static ?string $actionPipelinesConfigPrefix = null;

    /**
     * @param int $id
     *
     * @return Brand
     * @throws ModelNotFoundException
     */
    public function find(int $id) : Brand
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * @param int $id
     *
     * @return Brand|null
     * @throws  BrandException
     * @throws  ModelNotFoundException
     */
    public function isAllowUse(int $id) : ?Brand
    {
        return $this->find($id);

    }

    /**
     * to tree
     * @return array
     */
    public function tree() : array
    {
        $query = (new Brand())->withQuery(function (Model $query) {
            return $query->where('status', CategoryStatusEnum::ENABLE);
        });
        return $query->toTree();
    }

    public function lists() : \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()->paginate();
    }


    public function validator(array $data) : \Illuminate\Validation\Validator
    {
        return Validator::make($data, $this->rules(), [], $this->attributes());
    }

    protected function rules() : array
    {
        $table = (new Brand())->getTable();
        return [
            'name'      => [ 'required', 'max:100' ],
            'parent_id' => [ 'required', 'integer', new NotZeroExistsRule($table, 'id'), ],
            'logo'      => [ 'sometimes', 'max:255' ],
            'sort'      => [ 'integer' ],
            'status'    => [ new Enum(BrandStatusEnum::class) ],
            'extends'   => [ 'sometimes', 'array' ],
        ];

    }

    protected function attributes() : array
    {
        return [
            'name'      => '名称',
            'parent_id' => '父品牌',
            'logo'      => 'Logo',
            'sort'      => '排序',
            'status'    => '状态',
            'extends'   => '扩展字段',
        ];

    }

    /**
     * 修改
     *
     * @param int   $id
     * @param array $data
     *
     * @return Brand
     */
    public function modify(int $id, array $data) : Brand
    {
        $brand     = Brand::findOrFail($id);
        $validator = $this->validator($data);
        $validator->setRules(Arr::only($validator->getRules(), array_keys($data)));
        $validator->validate();
        $brand->fill($validator->safe()->all());
        $brand->updater = $this->getOperator();
        $brand->save();
        return $brand;

    }

}
