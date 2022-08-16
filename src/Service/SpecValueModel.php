<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Product\Metadata\SpecValueTrait;
use Wei\Model\SoftDeleteTrait;

/**
 * 规格值
 */
class SpecValueModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SnowflakeTrait;
    use SoftDeleteTrait;
    use SpecValueTrait;

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
