<?php

namespace MiaoxingTest\Product\Service;

use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\Product;
use Miaoxing\Product\Service\ProductModel;

class ProductTest extends BaseTestCase
{
    public function testCreate()
    {
        $ret = Product::create([
            'name' => '测试商品',
            'spec' => [
                'specs' => ProductModel::getDefaultSpecs(),
            ],
            'skus' => [
                [
                    'price' => 20,
                    'stockNum' => 10,
                    'specValues' => [
                        [
                            'name' => '默认',
                            'specName' => '默认',
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertRetSuc($ret);
        $this->assertInstanceOf(ProductModel::class, $ret['data']);
    }

    public function testCreateErr()
    {
        $ret = Product::create([]);
        $this->assertRetErr($ret);
    }
}
