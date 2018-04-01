<?php

namespace Miaoxing\Product\Model;

use Miaoxing\Product\Service\Product;

/**
 * @property Product $product
 */
trait BelongsToProductTrait
{
    public function product()
    {
        return $this->belongsTo(wei()->product());
    }
}
