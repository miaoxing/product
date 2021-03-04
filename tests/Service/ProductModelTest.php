<?php

namespace MiaoxingTest\Product\Service;

use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\ProductModel;
use Wei\Req;
use Wei\V;

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
                ProductModel::STATUS_ON_SALE
            ]
        ];
    }
}

