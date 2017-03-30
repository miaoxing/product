<?php

namespace Miaoxing\Product\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20170330181950CreateProductTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
//        $this->schema->table('product')
//            ->id()
//            ->int('categoryId')
//            ->string('name', 255)
//            ->string('type', 32)->comment('商品类型,如桶装水,水票')
//            ->string('template', 32)->comment('商品模板,如common,ticket')
//            ->tinyInt('listing', 1)->defaults(1)->comment('是否上架(1:上架,0:下架)')
//            ->timestamp('startTime')
//            ->timestamp('endTime')
//            ->int('brandId')
//            ->int('countryId')
//            ->int('supplierId')
//            ->decimal('price', 10)
//            ->int('quantity')
//            ->int('soldQuantity')
//            ->text('detail')
//            ->text('config')
//            ->string('thumb', 255)
//            ->text('images')
//            ->string('image', 255)
//            ->int('scores')
//            ->decimal('originalPrice', 10)
//            ->decimal('discount', 10)
//            ->string('no', 255)
//            ->int('limitation')
//            ->tinyInt('allowCoupon', 4)->defaults(1)
//            ->tinyInt('subAtPay', 1)->defaults(1)->comment('支付时减少库存1, 不减少库存0')
//            ->tinyInt('cashOnDelivery', 1)->comment('是否货到付款')
//            ->tinyInt('allowCashOnDelivery', 1)->comment('是否货到付款')
//            ->tinyInt('requireAddress', 1)->defaults(1)->comment('是否需要填写地址')
//            ->tinyInt('bonded', 1)->comment('是否为保税商品')
//            ->decimal('shippingFee', 10)
//            ->int('shippingTplId')
//            ->int('sort')
//            ->tinyInt('visible', 1)->defaults(1)->comment('是否在前后台列表可见')
//            ->text('skuConfigs')->comment('规格配置')
//            ->tinyInt('deleted', 4)
//            ->tinyInt('virtual', 1)
//            ->timestampsV1()
//            ->userstampsV1()
//            ->softDeletableV1()
//            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('product');
    }
}
