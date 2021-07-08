<?php

namespace MiaoxingTest\Prodcut\Pages\AdminApi\Products;

use Miaoxing\Plugin\Service\Tester;
use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\ProductModel;

class IdTest extends BaseTestCase
{
    public function testGet()
    {
        $product = ProductModel::save([
            'name' => '测试商品',
        ]);

        $ret = Tester::getAdminApi('products/' . $product->id);
        $this->assertRetSuc($ret);
        $this->assertSame('测试商品', $ret['data']['name']);

        $product->destroy();
        $this->expectExceptionObject(new \Exception('Record not found', 404));
        Tester::getAdminApi('products/' . $product->id);
    }

    public function testGet404()
    {
        $this->expectExceptionObject(new \Exception('Record not found', 404));

        Tester::getAdminApi('products/not-found');
    }

    public function testPatch()
    {
        $product = ProductModel::save();

        $ret = Tester::patchAdminApi('products/' . $product->id, [
            'name' => '测试',
            'sort' => 60,
            'intro' => '简介',
        ]);

        /** @var ProductModel $newProduct */
        $newProduct = $ret['data'];

        $this->assertSame($product->id, $newProduct->id);
        $this->assertNotEquals($product->name, $newProduct->name);
        $this->assertSame('测试', $newProduct->name);
        $this->assertSame(60, $newProduct->sort);
        $this->assertSame('简介', $newProduct->intro);
    }

    public function testList()
    {
        $product = ProductModel::save();

        $ret = Tester::patchAdminApi('products/' . $product->id, [
            'isListing' => false,
        ]);

        /** @var ProductModel $newProduct */
        $newProduct = $ret['data'];
        $this->assertFalse($newProduct->isListing);

        $ret = Tester::patchAdminApi('products/' . $product->id, [
            'isListing' => true,
        ]);

        /** @var ProductModel $newProduct */
        $newProduct = $ret['data'];
        $this->assertTrue($newProduct->isListing);
    }

    public function testPostNameTooLong()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => str_repeat('我', 256),
        ]);
        $this->assertRetErr($ret, '名称最多只能包含255个字符');
    }

    public function testPostWithoutSpec()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => '测试',
        ]);
        $this->assertRetErr($ret, '规格不能为空');
    }

    public function testPostWithoutSkus()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => '测试',
            'spec' => [
                'specs' => [
                    [
                        'name' => '默认',
                        'values' => [
                            [
                                'name' => '默认',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertRetErr($ret, '规格明细不能为空');
    }

    public function testPost()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => '测试',
            'spec' => [
                'specs' => [
                    [
                        'name' => '默认',
                        'values' => [
                            [
                                'name' => '默认',
                            ],
                        ],
                    ],
                ],
            ],
            'skus' => [
                [
                    'price' => 3,
                    'stockNum' => 4,
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
    }

    public function testPostIsDefault()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => '测试',
            'spec' => [
                'specs' => ProductModel::getDefaultSpecs(),
            ],
            'skus' => [
                [
                    'price' => 3,
                    'stockNum' => 4,
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

        /** @var ProductModel $product */
        $product = $ret['data'];

        $this->assertTrue($product->spec->isDefault);
    }

    public function testPostIsDefaultFalse()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => '测试',
            'spec' => [
                'specs' => [
                    [
                        'name' => '默认',
                        'values' => [
                            [
                                'name' => '默认2',
                            ],
                        ],
                    ],
                ],
            ],
            'skus' => [
                [
                    'price' => 3,
                    'stockNum' => 4,
                    'specValues' => [
                        [
                            'name' => '默认2',
                            'specName' => '默认',
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertRetSuc($ret);

        /** @var ProductModel $product */
        $product = $ret['data'];

        $this->assertFalse($product->spec->isDefault);
    }

    public function testInvalidCategoriesProducts()
    {
        $ret = Tester::postAdminApi('products', [
            'name' => 'test',
            'categoriesProducts' => [
                [
                    'categoryId' => 2 ^ 32,
                ],
            ],
        ]);
        $this->assertRetErr($ret, '第 1 个分类的值不存在');
    }
}
