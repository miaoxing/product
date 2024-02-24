<?php

namespace Miaoxing\Product\Task;

use Miaoxing\Plugin\Schedule\Task;
use Miaoxing\Product\Service\ProductModel;
use Wei\Time;

/**
 * 更新商品的定时开始和结束状态
 */
class UpdateTimingStatus extends Task
{
    public function run()
    {
        // 1. 定时开始
        $products = ProductModel::where('status', ProductModel::STATUS_NOT_STARTED)
                ->where('is_listing', true)
                ->where('stock_num', '>=', 0)
                ->where('start_at', '<', Time::now())
                ->where(static function (ProductModel $model) {
                    $model->where('end_at', '>', Time::now())->orWhereNull('end_at');
                })
                ->all();
        foreach ($products as $product) {
            $product->updateStatus();
        }

        // 2. 定时结束
        $products = ProductModel
            // 有可能脚本未运行，或是配置错误，导致未开始已经结束
            ::whereIn('status', [ProductModel::STATUS_NOT_STARTED, ProductModel::STATUS_ON_SALE])
                ->where('is_listing', true)
                ->where('stock_num', '>=', 0)
                ->where('end_at', '<', Time::now())
                ->all();
        foreach ($products as $product) {
            $product->updateStatus();
        }
    }
}
