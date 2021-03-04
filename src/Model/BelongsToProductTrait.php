<?php

namespace Miaoxing\Product\Model;

use Miaoxing\Product\Service\ProductModel;

/**
 * 为模型添加属于商品模型的关联
 *
 * @property ProductModel $product
 */
trait BelongsToProductTrait
{
    public function product()
    {
        return $this->belongsTo(ProductModel::class);
    }
}
