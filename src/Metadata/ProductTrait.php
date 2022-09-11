<?php

namespace Miaoxing\Product\Metadata;

/**
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $outerId 外部编号
 * @property string $shippingTplId 运费模板编号
 * @property string $name 名称
 * @property string $intro 简介
 * @property string $minPrice 最低的销售价
 * @property string $minMarketPrice 最低销售价的划线价
 * @property int $minScore 最低的积分
 * @property int $stockNum 库存
 * @property int $soldNum 销量
 * @property string $image 主图
 * @property int $status 状态，具体见模型常量
 * @property bool $isListing 是否上架
 * @property bool $isHidden 是否隐藏不可见
 * @property bool $isInList 是否显示在前台列表，根据状态等计算得出
 * @property string|null $startAt 开始销售时间
 * @property string|null $endAt 结束销售时间
 * @property int $maxOrderQuantity 最大购买数量
 * @property int $decStockMode 库存计数。1:付款减库存;2:拍下减库存
 * @property bool $isAllowAddCart 是否可加入购物车
 * @property bool $isAllowCoupon 是否可使用优惠券
 * @property bool $isRequireAddress 支付时是否需填写地址
 * @property bool $isAllowComment 支付时是否允许留言
 * @property int $sort 顺序，从到到小排列
 * @property string $configs 配置
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 * @property string|null $deletedAt
 * @property string $deletedBy
 * @property string|null $purgedAt
 * @property string $purgedBy
 * @internal will change in the future
 */
trait ProductTrait
{
}
