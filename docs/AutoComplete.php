<?php

namespace plugins\product\docs {

    /**
     * @property    \Miaoxing\Product\Service\Product $product 商品服务
     * @method      \Miaoxing\Product\Service\Product|\Miaoxing\Product\Service\Product[] product()
     *
     * @method      \Miaoxing\Product\Service\Sku|\Miaoxing\Product\Service\Sku[] sku()
     *
     * @property    \Miaoxing\Product\Service\Money $money 金额服务
     */
    class AutoComplete
    {
    }
}

namespace {

    /**
     * @return \plugins\product\docs\AutoComplete
     */
    function wei()
    {
    }

    $product = wei()->product();
}
