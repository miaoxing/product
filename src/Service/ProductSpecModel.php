<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Product\Metadata\ProductSpecTrait;

/**
 * 商品规格
 */
class ProductSpecModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use ProductSpecTrait;
    use SnowflakeTrait;
}
