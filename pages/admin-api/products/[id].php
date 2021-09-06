<?php

use Miaoxing\Plugin\BaseController;
use Miaoxing\Product\Service\Product;
use Miaoxing\Product\Service\ProductModel;
use Miaoxing\Services\Page\ItemTrait;

return new class extends BaseController {
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
