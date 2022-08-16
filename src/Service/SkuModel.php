<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Product\Metadata\SkuTrait;
use Miaoxing\Product\Model\BelongsToProductTrait;
use Wei\Model\SoftDeleteTrait;
use Wei\Ret;

/**
 * SKU
 *
 * @property array $specValueIds
 */
class SkuModel extends BaseModel
{
    use BelongsToProductTrait;
    use HasAppIdTrait;
    use ModelTrait;
    use SkuTrait;
    use SnowflakeTrait;
    use SoftDeleteTrait;

    protected $attributes = [
        'specValueIds' => [],
    ];

    protected $columns = [
        'specValueIds' => [
            'cast' => [
                'list',
                'type' => 'string',
            ],
        ],
    ];

    public function getGuarded()
    {
        return array_merge($this->guarded, [
            'soldNum',
        ]);
    }

    /**
     * 检查当前 SKU 是否可购买
     *
     * @return Ret
     */
    public function checkCreateCart(): Ret
    {
        // 加入购物车之后，再删除 SKU 的情况
        if ($this->isDeleted()) {
            return err('该商品规格已下架');
        }

        if ($this->stockNum <= 0) {
            return err('该商品规格已售罄');
        }

        return suc('可以购买');
    }
}
