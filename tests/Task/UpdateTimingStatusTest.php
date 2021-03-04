<?php

namespace MiaoxingTest\Product\Task;

use Miaoxing\Plugin\Test\BaseTestCase;
use Miaoxing\Product\Service\ProductModel;
use Miaoxing\Product\Task\UpdateTimingStatus;

class UpdateTimingStatusTest extends BaseTestCase
{
    public function testOnSaleNotChange()
    {
        $product = ProductModel::saveAttributes([
            'isListing' => true,
            'stockNum' => 1,
            'status' => ProductModel::STATUS_ON_SALE,
            'startAt' => date('Y-m-d H:i:s', time() - 1),
            'endAt' => date('Y-m-d H:i:s', time() + 1),
        ]);
        $this->runTask();

        $product->reload();
        $this->assertSame(ProductModel::STATUS_ON_SALE, $product->status);
    }

    public function testNotStatedToOnSale()
    {
        $product = ProductModel::saveAttributes([
            'isListing' => true,
            'stockNum' => 1,
            'status' => ProductModel::STATUS_NOT_STARTED,
            'startAt' => date('Y-m-d H:i:s', time() - 1),
        ]);

        $this->runTask();

        $product->reload();
        $this->assertSame(ProductModel::STATUS_ON_SALE, $product->status);
    }

    public function testOnSaleToEnded()
    {
        $product = ProductModel::saveAttributes([
            'isListing' => true,
            'stockNum' => 1,
            'status' => ProductModel::STATUS_ON_SALE,
            'endAt' => date('Y-m-d H:i:s', time() - 1),
        ]);

        $this->runTask();

        $product->reload();
        $this->assertSame(ProductModel::STATUS_ENDED, $product->status);
    }

    public function testNotStatedToOnSaleWithEndAt()
    {
        $product = ProductModel::saveAttributes([
            'isListing' => true,
            'stockNum' => 1,
            'status' => ProductModel::STATUS_NOT_STARTED,
            'startAt' => date('Y-m-d H:i:s', time() - 1),
            'endAt' => date('Y-m-d H:i:s', time() + 1),
        ]);

        $this->runTask();

        $product->reload();
        $this->assertSame(ProductModel::STATUS_ON_SALE, $product->status);
    }

    public function testNotStartedToEnded()
    {
        $product = ProductModel::saveAttributes([
            'isListing' => true,
            'stockNum' => 1,
            'status' => ProductModel::STATUS_NOT_STARTED,
            'startAt' => date('Y-m-d H:i:s', time() - 1),
            'endAt' => date('Y-m-d H:i:s', time() - 1),
        ]);

        $this->runTask();

        $product->reload();
        $this->assertSame(ProductModel::STATUS_ENDED, $product->status);
    }

    protected function runTask()
    {
        $task = new UpdateTimingStatus();
        $task();
    }
}
