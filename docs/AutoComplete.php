<?php

namespace plugins\product\docs {

    /**
     * @property    \Miaoxing\Product\Service\Product $product 商品服务
     * @method      \Miaoxing\Product\Service\Product|\Miaoxing\Product\Service\Product[] product()
     *
     * @method      \Miaoxing\Product\Service\Sku|\Miaoxing\Product\Service\Sku[] sku()
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
