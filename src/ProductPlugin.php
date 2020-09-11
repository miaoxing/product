<?php

namespace Miaoxing\Product;

class ProductPlugin extends \Miaoxing\Plugin\BasePlugin
{
    protected $name = '商品';

    protected $version = '1.0.0';

    protected $adminNavId = 'products';

    public function onAdminNavGetNavs(&$navs, &$categories, &$subCategories)
    {
        $categories['products'] = [
            'name' => '商品',
            'sort' => 900,
        ];

        $subCategories['products'] = [
            'parentId' => 'products',
            'name' => '商品',
            'icon' => 'fa fa-gift',
        ];

        $subCategories['products-service'] = [
            'parentId' => 'products',
            'name' => '商品服务',
            'icon' => 'fa fa-gear',
        ];

        $navs[] = [
            'parentId' => 'products',
            'url' => 'admin/products',
            'name' => '商品管理',
            'sort' => 900,
        ];

        $navs[] = [
            'parentId' => 'products',
            'url' => 'admin/product-categories',
            'name' => '栏目管理',
            'sort' => 800,
        ];

        $subCategories['product-setting'] = [
            'parentId' => 'products',
            'name' => '设置',
            'icon' => 'fa fa-gear',
            'sort' => 0,
        ];

        $navs[] = [
            'parentId' => 'product-setting',
            'url' => 'admin/product-settings',
            'name' => '功能设置',
            'sort' => 0,
        ];
    }

    public function onLinkToGetLinks(&$links, &$types)
    {
        foreach (wei()->category()->notDeleted()->withParent('mall')->desc('sort')->getTree() as $category) {
            $links[] = [
                'typeId' => 'mall',
                'name' => '商品栏目：' . $category['name'],
                'url' => 'products?categoryId=' . $category['id'],
            ];
        }
    }

    public function onPreProductListFind($req = null, $products = null)
    {
    }
}
