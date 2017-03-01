<?php

namespace Miaoxing\Product\Controller\Admin;

class ProductSettings extends \miaoxing\plugin\BaseController
{
    protected $controllerName = '商品功能设置';

    protected $actionPermissions = [
        'index,update' => '设置',
    ];

    public function indexAction()
    {
        return get_defined_vars();
    }

    public function updateAction($req)
    {
        // FIXME 统一前缀
        $this->setting->setValues((array) $req['settings'], ['product.', 'products.']);

        return $this->suc();
    }
}
