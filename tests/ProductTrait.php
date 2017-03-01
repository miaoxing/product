<?php

namespace MiaoxingTest\Product;

trait ProductTrait
{
    public function createProduct($data = [])
    {
        $product = wei()->product();
        $ret = $product->create($data + [
                'name' => '测试商品',
                'quantity' => 10,
                'price' => '10.00',
                'startTime' => date('Y-m-d H:i:s'),
                'endTime' => date('Y-m-d H:i:s', strtotime('+1 month')),
            ]);
        $this->assertRetSuc($ret, '操作成功', '创建商品成功');

        return $product;
    }
}
