<?php

namespace RedJasmine\Product\Services\Brand;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Enums\Brand\BrandStatusEnum;
use RedJasmine\Product\Exceptions\BrandException;
use RedJasmine\Product\Models\Brand;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Foundation\Service\WithUserService;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Support\Rules\NotZeroExistsRule;
use Spatie\QueryBuilder\AllowedFilter;

class BrandService extends Service
{

    use HasQueryBuilder;

    public function filters() : array
    {
        return [

            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
            static::searchFilter([ 'name' ]),
        ];
    }


    /**
     * @var string
     */
    protected string $model = Brand::class;


    /**
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function query()
    {
        return $this->queryBuilder();
    }

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


    /**
     * 品牌
     *
     * @param array $data
     *
     * @return Brand
     * @throws Exception
     */
    public function create(array $data) : Brand
    {
        $brand = Brand::where('name', $data['name'])->first();
        if (filled($brand)) {
            return $brand;
        }
        $validator = $this->validator($data);
        $validator->validate();
        $brand     = new Brand();
        $brand->id = $this->buildID();
        $brand->fill($validator->safe()->all());
        $brand->creator = $this->getOperator();
        $brand->save();
        return $brand;

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
     * @return int
     * @throws Exception
     */
    protected function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }

    /**
     * 更新
     *
     * @param int   $id
     * @param array $data
     *
     * @return Brand
     */
    public function update(int $id, array $data) : Brand
    {
        $brand     = Brand::findOrFail($id);
        $validator = $this->validator($data);
        $validator->validate();
        $brand->fill($validator->safe()->all());
        $brand->updater = $this->getOperator();
        $brand->save();
        return $brand;
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
