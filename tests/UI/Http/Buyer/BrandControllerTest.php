<?php

namespace RedJasmine\Product\Tests\UI\Http\Buyer;

class BrandControllerTest extends BaseTestCase
{


    public function test_can_index() : void
    {
        $response = $this->getJson(route('product.buyer.brands.index', [], false));

        $response->assertStatus(200);


        if ($id = $response->json('data.0.id')) {
            $showResponse = $this->getJson(route('product.buyer.brands.show', [ 'id' => $id ], false));
            $showResponse->assertStatus(200);
            $this->assertEquals($id, $showResponse->json('data.id'));
        }

    }

}
