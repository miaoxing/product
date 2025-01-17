<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;

/**
 * 商品图片模型
 *
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $productId 商品编号
 * @property int $type 图片类型，具体见模型常量
 * @property string $url 图片地址
 * @property string $description 图片说明
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 */
class ProductImageModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;

    public const TYPE_DEFAULT = 1;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'type' => self::TYPE_DEFAULT,
    ];
}
