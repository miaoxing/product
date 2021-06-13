<?php

namespace Miaoxing\Product\Resource;

use Miaoxing\Plugin\Resource\BaseResource;
use Miaoxing\Product\Service\ProductModel;

class ProductResource extends BaseResource
{
    public function transform(ProductModel $product): array
    {
        return [
            $this->extract($product, [
                'id',
                'name',
                'intro',
                'minPrice',
                'minMarketPrice',
                'minScore',
                'stockNum',
                'soldNum',
                'image',
                'startAt',
                'endAt',
                'maxOrderQuantity',
                'isAllowAddCart',
                'isAllowCoupon',
                'isRequireAddress',
                'isAllowComment',
                'configs',
            ]),
            'images' => ProductImageResource::whenLoaded($product, 'images'),
            'spec' => ProductSpecResource::whenLoaded($product, 'spec'),
            'detail' => ProductDetailResource::whenLoaded($product, 'detail'),
            'skus' => SkuResource::whenLoaded($product, 'skus'),
        ];
    }
}
