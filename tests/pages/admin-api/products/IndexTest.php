<?php

namespace MiaoxingTest\Prodcut\Pages\AdminApi\Products;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Service\User;
use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\ProductModel;

class IndexTest extends BaseTestCase
{
    public function testGet()
    {
        User::loginById(1);

        $product = ProductModel::save([
            'name' => '测试商品' . time(),
        ]);

        $ret = Tester::request(['search' => ['id' => $product->id]])->getAdminApi('products');

        $this->assertSame($product->name, $ret['data'][0]['name']);
    }
}
