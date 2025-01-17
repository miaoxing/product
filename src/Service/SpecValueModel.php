<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Wei\Model\SoftDeleteTrait;

/**
 * 规格值
 *
 * @property string|null $id 编号
 * @property string $appId 应用编号
 * @property string $specId 规格编号
 * @property string $name 名称
 * @property int $sort 顺序，从大到小排列
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string $createdBy
 * @property string $updatedBy
 * @property string|null $deletedAt
 * @property string $deletedBy
 * @property $this $spec 规格
 */
class SpecValueModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;
    use SoftDeleteTrait;

    /**
     * 规格
     *
     * @return $this
     */
    public function spec(): SpecModel
    {
        return $this->belongsTo(SpecModel::class);
    }
}
