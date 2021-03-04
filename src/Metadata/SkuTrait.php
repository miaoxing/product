<?php

namespace Miaoxing\Product\Metadata;

/**
 * @property int $id 编号
 * @property int $appId 应用编号
 * @property string $outerId 外部编号
 * @property int $productId 商品编号
 * @property string $specValueIds 多个规格值编号，使用,隔开
 * @property string $no 货号
 * @property float $price 销售价
 * @property float $marketPrice 划线价
 * @property int $score 所需积分
 * @property int $stockNum 库存
 * @property int $soldNum 销量
 * @property float $weight 重量（千克）
 * @property int $sort 顺序，从大到小排列
 * @property string $image 图片
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @property string|null $deletedAt
 * @property int $deletedBy
 * @internal will change in the future
 */
trait SkuTrait
{
}
