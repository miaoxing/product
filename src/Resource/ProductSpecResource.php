<?php

namespace Miaoxing\Product\Resource;

use Miaoxing\Plugin\Resource\BaseResource;
use Miaoxing\Product\Service\ProductSpecModel;

class ProductSpecResource extends BaseResource
{
    public function transform(ProductSpecModel $spec): array
    {
        return $spec->toArray([
            'isDefault',
            'specs',
        ]);
    }
}
