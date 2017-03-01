<?php

namespace MiaoxingTest\Product\Service;

use Miaoxing\Product\Service\Product;

class ProductTest extends \Miaoxing\Plugin\Test\BaseTestCase
{
    /**
     * @param array $data
     * @param int $status
     * @param string $coverName
     * @dataProvider dataForStatus
     */
    public function testStatus(array $data, $status, $coverName)
    {
        $product = $this->getModelServiceMock('product', ['getStock']);

        $product->expects($this->any())
            ->method('getStock')
            ->willReturn($data['quantity']);

        // @var Product $product

        $product->fromArray($data);

        $config = $product->getStatusConfig();
        $this->assertEquals($status, $config['status']);

        $this->assertEquals($coverName, $product->getCoverName());
    }

    public function dataForStatus()
    {
        return [
            [
                [
                    'listing' => true,
                    'quantity' => 10,
                ],
                \Miaoxing\Product\Service\Product::STATUS_ON_SALE,
                false,
            ],
            [
                [
                    'listing' => false,
                    'quantity' => 10,
                ],
                \Miaoxing\Product\Service\Product::STATUS_UNLISTED,
                '已下架',
            ],
            [
                [
                    'listing' => true,
                    'quantity' => 0,
                ],
                Product::STATUS_SOLD_OUT,
                '售罄',
            ],
            [
                [
                    'listing' => true,
                    'quantity' => 10,
                    'startTime' => date('Y-m-d H:i:s', time() + 100),
                ],
                \Miaoxing\Product\Service\Product::STATUS_NOT_STARTED,
                '即将开始',
            ],
            [
                [
                    'listing' => true,
                    'quantity' => 10,
                    'endTime' => date('Y-m-d H:i:s', time() - 100),
                ],
                Product::STATUS_ENDED,
                '已结束',
            ],
            [
                [
                    'listing' => true,
                    'quantity' => -1,
                ],
                Product::STATUS_SOLD_OUT,
                '售罄',
            ],
            [
                [
                    'listing' => false,
                    'quantity' => 0,
                ],
                Product::STATUS_UNLISTED,
                '已下架',
            ],
        ];
    }
}
