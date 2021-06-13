<?php

namespace Miaoxing\Product\Resource;

use Miaoxing\Plugin\Resource\BaseResource;
use Miaoxing\Product\Service\ProductImageModel;

class ProductImageResource extends BaseResource
{
    public function transform(ProductImageModel $image): array
    {
        return $image->toArray([
            'url',
            'description',
        ]);
    }
}
