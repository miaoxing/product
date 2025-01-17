<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Wei\Model\SoftDeleteTrait;

/**
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $categoryId 分类编号
 * @property string $productId 商品编号
 * @property int $sort 顺序，从大到小排列
 * @property string $createdBy
 * @property string $updatedBy
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string|null $deletedAt
 * @property string $deletedBy
 */
class CategoriesProductModel extends BaseModel
{
    use ModelTrait;
    use SnowflakeTrait;
    use SoftDeleteTrait;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'sort' => 50,
    ];
}
