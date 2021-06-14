<?php

use Miaoxing\Plugin\BaseController;
use Miaoxing\Product\Resource\ProductResource;
use Miaoxing\Product\Service\ProductModel;

return new class extends BaseController {
    public function get($req)
    {
        $product = ProductModel::findOrFail($req['id']);

        $product->load(['images', 'spec', 'skus']);

        if (strpos((string) $req['include'], 'detail') !== false) {
            $product->load('detail');
        }

        return $product->toRet(ProductResource::class)
            ->data('createCartOrOrder', $product->checkCreateCartOrOrder());
    }
};
