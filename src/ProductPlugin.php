<?php

namespace Miaoxing\Product;

use Illuminate\Console\Scheduling\Schedule;
use Miaoxing\Plugin\BasePlugin;
use Miaoxing\Product\Task\UpdateTimingStatus;

/**
 * 商品插件
 */
class ProductPlugin extends BasePlugin
{
    protected $name = '商品';

    protected $code = 208;

    /**
     * 添加后台菜单
     *
     * @param array $navs
     * @param array $categories
     * @param array $subCategories
     */
    public function onAdminNavGetNavs(array &$navs, array &$categories, array &$subCategories)
    {
        $categories['products'] = [
            'name' => '商品',
            'sort' => 900,
        ];

        $subCategories['products'] = [
            'parentId' => 'products',
            'name' => '商品管理',
            'url' => 'admin/products',
        ];

        $subCategories[] = [
            'parentId' => 'products',
            'name' => '分类管理',
            'url' => 'admin/categories',
        ];
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
