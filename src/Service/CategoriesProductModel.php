<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Product\Metadata\CategoriesProductTrait;
use Wei\Model\SoftDeleteTrait;

class CategoriesProductModel extends BaseModel
{
    use CategoriesProductTrait;
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
