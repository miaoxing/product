<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Product\Metadata\ProductDetailTrait;

class ProductDetailModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use ProductDetailTrait;
    use SnowflakeTrait;

    protected $attributes = [
        'content' => '',
    ];
}
