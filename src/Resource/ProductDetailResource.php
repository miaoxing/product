<?php

namespace Miaoxing\Product\Resource;

use Miaoxing\Plugin\Resource\BaseResource;
use Miaoxing\Product\Service\ProductDetailModel;

class ProductDetailResource extends BaseResource
{
    public function transform(ProductDetailModel $detail): array
    {
        return $detail->toArray([
            'content',
        ]);
    }
}
