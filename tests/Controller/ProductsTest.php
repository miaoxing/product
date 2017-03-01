<?php

namespace MiaoxingTest\Product\Controller;

class ProductsTest extends \Miaoxing\Plugin\Test\BaseControllerTestCase
{
    protected static $productId;

    public static function setUpBeforeClass()
    {
        // 创建测试商品
        $product = wei()->product();
        $ret = $product->create([
            'name' => '隐藏价格测试商品',
            'quantity' => 1000,
            'price' => '20.01',
            'images' => [
                '/assets/mall/product/placeholder.gif',
            ],
        ]);
        assert(1 === $ret['code']);
        static::$productId = $product['id'];
    }

    public function testHidePriceInIndex()
    {
        wei()->setting->setValue('product.hidePrice', false);

        $res = wei()->tester()
            ->controller('products')
            ->exec()
            ->response();

        $this->assertContains('product-list-price', $res);

        wei()->setting->setValue('product.hidePrice', true);

        $res = wei()->tester()
            ->controller('products')
            ->exec()
            ->response();

        $this->assertNotContains('product-list-price', $res);

        wei()->setting->setValue('product.hidePrice', false);
    }

    public function testHidePriceInShow()
    {
        wei()->setting->setValue('product.hidePrice', false);

        $res = wei()->tester()
            ->controller('products')
            ->action('show')
            ->request(['id' => static::$productId])
            ->exec()
            ->response();

        $this->assertContains('product-price', $res);

        wei()->setting->setValue('product.hidePrice', true);

        $res = wei()->tester()
            ->controller('products')
            ->action('show')
            ->request(['id' => static::$productId])
            ->exec()
            ->response();

        $this->assertNotContains('product-price', $res);
    }
}
