<?php

use Miaoxing\Plugin\BasePage;
use Miaoxing\Product\Resource\ProductResource;
use Miaoxing\Product\Service\ProductModel;

return new class () extends BasePage {
    public function get($req)
    {
        $product = ProductModel::findOrFail($req['id']);

        $product->load(['images', 'spec', 'skus']);

        if (false !== strpos((string) $req['include'], 'detail')) {
            $product->load('detail');
        }

        return $product->toRet(ProductResource::class)
            ->data('createCartOrOrder', $product->checkCreateCartOrOrder());
    }
};
