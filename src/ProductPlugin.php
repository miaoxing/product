<?php

namespace Miaoxing\Product;

use Illuminate\Console\Scheduling\Schedule;
use Miaoxing\Admin\Service\AdminMenu;
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
        $products->addChild()->setUrl('admin/products/new')->setLabel('添加');
        $products->addChild()->setUrl('admin/products/[id]/edit')->setLabel('编辑');
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
