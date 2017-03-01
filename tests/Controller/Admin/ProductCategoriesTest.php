<?php

namespace MiaoxingTest\Product\Controller\Admin;

class ProductCategoriesTest extends \Miaoxing\Plugin\Test\BaseControllerTestCase
{
    public function testCreate()
    {
        $req = [
            'parentId' => 'mall',
            'name' => '测试栏目',
            'sort' => '50',
            'description' => '简介',
        ];
        wei()->request->set($req);

        $response = $this->dispatch('admin/productCategories', 'create');
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(1, $result['code']);
        $id = $result['data']['id'];

        $category = wei()->category()->findOneById($id);

        $this->assertArraySubset($req, $category->toArray());
    }
}
