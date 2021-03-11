<?php

namespace MiaoxingTest\Prodcut\Pages\AdminApi\Products\Defaults;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;

class IndexTest extends BaseTestCase
{
    public function testGet()
    {
        $ret = Tester::getAdminApi('products/defaults');

        $this->assertRetSuc($ret);

        $data = $ret['data'];
        $this->assertArrayContains([
            'skus' => [],
            'images' => [],
            'categoriesProducts' => [],
        ], $data);

        $spec = $data['spec']['specs'][0];
        $this->assertIsArray($spec);

        $this->assertSame('默认', $spec['name']);
        $this->assertSame('默认', $spec['values'][0]['name']);
    }
}
