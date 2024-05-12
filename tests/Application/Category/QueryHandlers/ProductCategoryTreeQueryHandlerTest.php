<?php


namespace RedJasmine\Product\Tests\Application\Category\QueryHandlers;

use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductCategoryTreeQueryHandlerTest extends ApplicationTestCase
{

    protected function productCategoryReadRepository() : ProductCategoryReadRepositoryInterface
    {
        return app(ProductCategoryReadRepositoryInterface::class);

    }


    public function test_can_tree_query() : void
    {
        $query = ProductCategoryTreeQuery::from([
                                                    'fields' => [ 'id','parent_id','name' ]
                                                ]);

        $result = $this->productCategoryReadRepository()->tree($query);
        dd($result);
        $this->assertTrue(true);
    }

}
