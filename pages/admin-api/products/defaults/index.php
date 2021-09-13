<?php

use Miaoxing\Plugin\BaseController;
use Miaoxing\Product\Service\Product;
use Miaoxing\Product\Service\ProductModel;

return new class extends BaseController {
    public function get()
    {
        $data = array_merge(ProductModel::toArray(), [
            'skus' => [],
            'images' => [],
            'categoriesProducts' => [],
            'spec' => [
                'specs' => Product::getDefaultSpecs(),
            ],
        ]);

        return suc(['data' => $data]);
    }
};
