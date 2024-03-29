<?php

namespace Miaoxing\Product\Metadata;

/**
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $outerId 外部编号
 * @property string $productId 商品编号
 * @property string $specValueIds 多个规格值编号，使用,隔开
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
 * @internal will change in the future
 */
trait SkuTrait
{
}
