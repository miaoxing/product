<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SoftDeleteTrait;
use Miaoxing\Product\Metadata\CategoriesProductTrait;
use Miaoxing\Product\Metadata\CategoryProductTrait;

class CategoriesProductModel extends BaseModel
{
    use ModelTrait;
    use CategoriesProductTrait;
    use SoftDeleteTrait;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'sort' => 50,
    ];
}
