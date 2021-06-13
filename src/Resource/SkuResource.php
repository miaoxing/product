<?php

namespace Miaoxing\Product\Resource;

use Miaoxing\Plugin\Resource\BaseResource;
use Miaoxing\Product\Service\SkuModel;

class SkuResource extends BaseResource
{
    public function transform(SkuModel $sku): array
    {
        return $sku->toArray([
            'id',
            'specValueIds',
            'price',
            'marketPrice',
            'score',
            'stockNum',
            'soldNum',
            'image',
        ]);
    }
}
