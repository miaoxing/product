<?php

namespace Miaoxing\Product\Controller\Admin;

class ProductCategories extends \Miaoxing\Category\Controller\Admin\Category
{
    protected $adminNavId = 'products';

    protected $controllerName = '栏目管理';

    protected $controllerPermissionName = '商品栏目管理';

    protected $actionPermissions = [
        'index' => '列表',
        'new,create' => '添加',
        'edit,update' => '编辑',
        'destroy' => '删除',
    ];

    protected $displayPageHeader = true;
}
