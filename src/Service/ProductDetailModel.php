<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Product\Metadata\ProductDetailTrait;

class ProductDetailModel extends BaseModel
{
    use ModelTrait;
    use ProductDetailTrait;

    protected $attributes = [
        'content' => '',
    ];
}
