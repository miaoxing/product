<?php

use Miaoxing\Plugin\BasePage;
use Miaoxing\Product\Service\Product;
use Miaoxing\Product\Service\ProductModel;
use Miaoxing\Services\Page\ItemTrait;

return new class () extends BasePage {
    use ItemTrait;

    protected $include = [
        'images',
        'spec',
        'skus',
        'detail',
        'categoriesProducts',
    ];

    public function patch($req)
    {
        $product = ProductModel::findFromReq($req);
        return Product::createOrUpdate($req, $product);
    }
};
