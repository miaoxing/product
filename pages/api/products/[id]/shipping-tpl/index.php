<?php

use Miaoxing\Logistics\Resource\ShippingTplResource;
use Miaoxing\Plugin\BasePage;
use Miaoxing\Product\Service\ProductModel;

return new class () extends BasePage {
    public function get($req)
    {
        $product = ProductModel::findOrFail($req['id']);
        if (!$product->isRequireAddress) {
            return suc(['detail' => '购买该商品无需填写地址']);
        }

        $shippingTpl = $product->shippingTpl;

        $city = '';
        if ($req['filterRulesByCity']) {
            ['city' => $city, 'rules' => $rules] = $shippingTpl->getCityAndRules('深圳市');
            if ($rules) {
                $shippingTpl->setRelationValue('rules', $rules);
            }
        }

        $shippingTpl->load('rules.service');

        return $shippingTpl->toRet(ShippingTplResource::class)
            ->with('city', $city);
    }
};
