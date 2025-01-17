<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Product\Model\BelongsToProductTrait;
use Wei\Model\SoftDeleteTrait;
use Wei\Ret;

/**
 * SKU
 *
 * @property array $specValueIds 多个规格值编号，使用,隔开
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $outerId 外部编号
 * @property string $productId 商品编号
 * @property string $no 货号
 * @property string $price 销售价
 * @property string $marketPrice 划线价
 * @property int $score 所需积分
 * @property int $stockNum 库存
 * @property int $soldNum 销量
 * @property string $weight 重量（千克）
 * @property int $sort 顺序，从大到小排列
 * @property string $image 图片
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 * @property string|null $deletedAt
 * @property string $deletedBy
 */
class SkuModel extends BaseModel
{
    use BelongsToProductTrait;
    use HasAppIdTrait;
    use ModelTrait;
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
