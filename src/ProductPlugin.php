<?php

namespace Miaoxing\Product;

use Illuminate\Console\Scheduling\Schedule;
use Miaoxing\Admin\Service\AdminMenu;
use Miaoxing\App\Service\PermissionMap;
use Miaoxing\Plugin\BasePlugin;
use Miaoxing\Product\Task\UpdateTimingStatus;

/**
 * 商品插件
 */
class ProductPlugin extends BasePlugin
{
    protected $name = '商品';

    protected $code = 208;

    public function onAdminMenuGetMenus(AdminMenu $menu)
    {
        $product = $menu->child('product')->setLabel('商品')->setSort(900);

        $products = $product->addChild()->setLabel('商品管理')->setUrl('admin/products')->setSort(900);
        $products->addChild()->setLabel('添加')->setUrl('admin/products/new');
        $products->addChild()->setLabel('编辑')->setUrl('admin/products/[id]/edit');
        $products->addChild()->setLabel('删除')->setUrl('admin/products/[id]/delete');
    }

    public function onPermissionGetMap(PermissionMap $map)
    {
        $map->addList('admin/products');
        $map->addNew('admin/products', [
            'GET api/admin/categories',
            'GET api/admin/shipping-tpls',
        ]);
        $map->addEdit('admin/products', [
            'GET api/admin/categories',
            'GET api/admin/shipping-tpls',
        ]);
        $map->addDelete('admin/products');
    }

    /**
     * 添加自定义定时任务
     *
     * @param Schedule $schedule
     */
    public function onSchedule(Schedule $schedule)
    {
        $schedule->call(new UpdateTimingStatus())->everyMinute();
    }
}
