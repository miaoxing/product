<?php

namespace MiaoxingTest\Prodcut\Pages\Api\Products;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\ProductModel;

class IdTest extends BaseTestCase
{
    public function testGet()
    {
        $product = ProductModel::saveAttributes([
            'name' => '测试商品',
            'status' => ProductModel::STATUS_ON_SALE,
        ]);

        $ret = Tester::get('/api/products/' . $product->id);
        $this->assertRetSuc($ret);
        $this->assertSame('测试商品', $ret['data']['name']);

        $this->assertArrayHasKey('createCartOrOrder', $ret['data']);

        $product->destroy();
        $this->expectExceptionObject(new \Exception('Record not found', 404));
        Tester::get('/api/products/' . $product->id);
    }

    public function testGet404()
    {
        $this->expectExceptionObject(new \Exception('Record not found', 404));

        Tester::get('/api/products/not-found');
    }
}
