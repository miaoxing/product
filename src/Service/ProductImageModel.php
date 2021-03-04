<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Product\Metadata\ProductImageTrait;

/**
 * 商品图片模型
 */
class ProductImageModel extends BaseModel
{
    use ModelTrait;
    use ProductImageTrait;
    use HasAppIdTrait;

    /**
     *
     */
    const TYPE_DEFAULT = 1;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'type' => self::TYPE_DEFAULT,
    ];
}
