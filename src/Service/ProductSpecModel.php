<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;

/**
 * 商品规格
 *
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $productId 商品编号
 * @property bool $isDefault 是否默认规格
 * @property array $specs 规格内容，内容如：[{id,values:[{id,name,image},...]},...]
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 */
class ProductSpecModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;
}
