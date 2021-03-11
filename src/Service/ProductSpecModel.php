<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Product\Metadata\ProductSpecTrait;

/**
 * 商品规格
 */
class ProductSpecModel extends BaseModel
{
    use ModelTrait;
    use ProductSpecTrait;
}
