<?php

namespace MiaoxingTest\Product\Service;

use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\SkuModel;
use Wei\Ret;
use Wei\Time;

class SkuModelTest extends BaseTestCase
{
    /**
     * @param array $attributes
     * @param Ret $ret
     * @dataProvider providerForCheckCreateCart
     */
    public function testCheckCreateCart(array $attributes, Ret $ret): void
    {
        $sku = SkuModel::new($attributes);
        $createCart = $sku->checkCreateCart();

        if ($createCart->isErr()) {
            $this->assertRetErr($ret, $ret['message']);
        } else {
            $this->assertRetSuc($ret, $ret['message']);
        }
    }

    public static function providerForCheckCreateCart(): array
    {
        return [
            [
                [
                    'deletedAt' => Time::now(),
                ],
                err('该商品规格已下架'),
            ],
            [
                [
                    'stockNum' => 0,
                ],
                err('该商品规格已售罄'),
            ],
            [
                [
                    'stockNum' => -1,
                ],
                err('该商品规格已售罄'),
            ],
            [
                [],
                suc('可以购买'),
            ],
        ];
    }
}
