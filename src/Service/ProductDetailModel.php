<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;

/**
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $productId 商品编号
 * @property string $content 内容
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 */
class ProductDetailModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;

    protected $attributes = [
        'content' => '',
    ];
}
