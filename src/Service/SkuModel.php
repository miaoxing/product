<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Product\Metadata\SkuTrait;
use Miaoxing\Product\Model\BelongsToProductTrait;

/**
 * SKU
 */
class SkuModel extends BaseModel
{
    use BelongsToProductTrait;
    use ModelTrait;
    use SkuTrait;
    use SoftDeleteTrait;

    protected $attributes = [
        'specValueIds' => [],
    ];

    protected $columns = [
        'specValueIds' => [
            'cast' => [
                'list',
                'type' => 'int',
            ],
        ],
    ];

    public function getGuarded()
    {
        return array_merge($this->guarded, [
            'soldNum',
        ]);
    }
}
