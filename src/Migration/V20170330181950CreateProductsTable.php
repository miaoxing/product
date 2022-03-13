<?php

namespace Miaoxing\Product\Migration;

use Wei\Migration\BaseMigration;

class V20170330181950CreateProductsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('products')->tableComment('商品')
            ->bigId()->comment('编号')
            ->uBigInt('app_id')->comment('应用编号')
            ->string('outer_id', 36)->comment('外部编号')
            ->uBigInt('shipping_tpl_id')->comment('运费模板编号')
            ->string('name')->comment('名称')
            ->string('intro')->comment('简介')
            ->uDecimal('min_price', 10)->comment('最低的销售价')
            ->uDecimal('min_market_price', 10)->comment('最低销售价的划线价')
            ->uInt('min_score')->comment('最低的积分')
            ->uInt('stock_num')->comment('库存')
            ->uInt('sold_num')->comment('销量')
            ->string('image')->comment('主图')
            ->uTinyInt('status')->comment('状态，具体见模型常量')
            ->bool('is_listing')->comment('是否上架')->defaults(true)
            ->bool('is_hidden')->comment('是否隐藏不可见')
            ->bool('is_in_list')->comment('是否显示在前台列表，根据状态等计算得出')
            ->datetime('start_at')->comment('开始销售时间')
            ->datetime('end_at')->comment('结束销售时间')
            ->uSmallInt('max_order_quantity')->comment('最大购买数量')
            ->uTinyInt('dec_stock_mode')->comment('库存计数。1:付款减库存;2:拍下减库存')->defaults(2)
            ->bool('is_allow_add_cart')->comment('是否可加入购物车')->defaults(true)
            ->bool('is_allow_coupon')->comment('是否可使用优惠券')->defaults(true)
            ->bool('is_require_address')->comment('支付时是否需填写地址')->defaults(true)
            ->bool('is_allow_comment')->comment('支付时是否允许留言')->defaults(true)
            ->uSmallInt('sort')->comment('顺序，从到到小排列')->defaults(50)
            ->string('configs', 1024)->comment('配置')->defaults('{}')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->index('name')
            ->index('min_price')
            ->index('status')
            ->exec();

        $this->schema->table('product_details')->tableComment('商品详情；1:1')
            ->bigId()->comment('编号')
            ->uBigInt('app_id')->comment('应用编号')
            ->uBigInt('product_id')->comment('商品编号')
            ->mediumText('content')->comment('内容')
            ->timestamps()
            ->userstamps()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists(['products', 'product_details']);
    }
}
