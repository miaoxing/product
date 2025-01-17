<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Wei\Model\SoftDeleteTrait;

/**
 * 规格
 *
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $name 规格名称
 * @property int $sort 顺序，从大到小排列
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 * @property string|null $deletedAt
 * @property string $deletedBy
 */
class SpecModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;
    use SoftDeleteTrait;
}
