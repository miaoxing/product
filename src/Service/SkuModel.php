<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Product\Metadata\SkuTrait;

/**
 * SKU
 */
class SkuModel extends BaseModel
{
    use ModelTrait;
    use SkuTrait;

    protected $attributes = [
        'specValueIds' => [],
    ];

    protected $columns = [
        'specValueIds' => [
            'cast' => [
                'list',
                'type' => 'int',
            ]
        ],
    ];

    public function getGuarded()
    {
        return array_merge($this->guarded, [
            'soldNum',
        ]);
    }
}
