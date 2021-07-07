<?php

namespace MiaoxingTest\Product\Service;

use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\ProductModel;
use Wei\Ret;

class ProductModelTest extends BaseTestCase
{
    /**
     * @dataProvider providerForCalStatus
     */
    public function testCalStatus(array $attributes, int $status): void
    {
        $product = ProductModel::new($attributes);
        $this->assertSame($status, $product->calStatus());
    }

    public static function providerForCalStatus(): array
    {
        return [
            [
                [
                    'isListing' => false,
                ],
                ProductModel::STATUS_DELISTED,
            ],
            [
                [
                    'stockNum' => 0,
                ],
                ProductModel::STATUS_SOLD_OUT,
            ],
            [
                [
                    'stockNum' => -1,
                ],
                ProductModel::STATUS_SOLD_OUT,
            ],
            [
                [
                    'stockNum' => 1,
                    'startAt' => date('Y-m-d H:i:s', strtotime('+1 day')),
                ],
                ProductModel::STATUS_NOT_STARTED,
            ],
            [
                [
                    'stockNum' => 1,
                    'endAt' => date('Y-m-d H:i:s', strtotime('-1 day')),
                ],
                ProductModel::STATUS_ENDED,
            ],
            [
                [
                    'stockNum' => 1,
                ],
                ProductModel::STATUS_ON_SALE,
            ],
        ];
    }

    /**
     * @param array $attributes
     * @param Ret $createCart
     * @param Ret|null $createOrder
     * @dataProvider providerForCheckCreateCartOrOrder
     */
    public function testCheckCreateCartOrOrder(array $attributes, Ret $createCart, Ret $createOrder = null): void
    {
        $product = ProductModel::new($attributes);
        $ret = $product->checkCreateCartOrOrder();

        if ($createCart->isErr()) {
            $this->assertRetErr($ret['createCart'], $createCart['message']);
        } else {
            $this->assertRetSuc($ret['createCart']);
        }

        if (!$createOrder) {
            $createOrder = $createCart;
        }
        if ($createOrder->isErr()) {
            $this->assertRetErr($ret['createOrder'], $createOrder['message']);
        } else {
            $this->assertRetSuc($ret['createOrder']);
        }

        if ($createCart->isErr() && $createOrder->isErr()) {
            $this->assertRetErr($ret, $createCart['message']);
        } else {
            $this->assertRetSuc($ret);
        }
    }

    public static function providerForCheckCreateCartOrOrder(): array
    {
        return [
            [
                [
                    'status' => ProductModel::STATUS_NOT_STARTED,
                ],
                suc(),
                err('抢购即将开始'),
            ],
            [
                [
                    'status' => ProductModel::STATUS_ON_SALE,
                ],
                suc(),
            ],
            [
                [
                    'status' => ProductModel::STATUS_ENDED,
                ],
                err('抢购结束'),
            ],
            [
                [
                    'status' => ProductModel::STATUS_SOLD_OUT,
                ],
                err('商品已卖光了'),
            ],
            [
                [
                    'status' => ProductModel::STATUS_DELISTED,
                ],
                err('商品已下架'),
            ],
            [
                [
                    'status' => ProductModel::STATUS_DELETED,
                ],
                err('商品已删除'),
            ],
            [
                [
                    'status' => ProductModel::STATUS_ON_SALE,
                    'isAllowAddCart' => false,
                ],
                err('该商品不可加入购物车'),
                suc(),
            ],
        ];
    }
}
