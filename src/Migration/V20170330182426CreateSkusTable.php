<?php

namespace Miaoxing\Product\Migration;

use Wei\Migration\BaseMigration;

class V20170330182426CreateSkusTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('skus')->tableComment('商品SKU；1:m')
            ->bigId()->comment('编号')
            ->uBigInt('app_id')->comment('应用编号')
            ->string('outer_id', 36)->comment('外部编号')
            ->uBigInt('product_id')->comment('商品编号')
            ->string('spec_value_ids', 64)->comment('多个规格值编号，使用,隔开')
            ->string('no', 16)->comment('货号')
            ->uDecimal('price')->comment('销售价')
            ->uDecimal('market_price')->comment('划线价')
            ->uMediumInt('score')->comment('所需积分')
            ->uInt('stock_num')->comment('库存')
            ->uInt('sold_num')->comment('销量')
            ->uDecimal('weight', 10, 3)->comment('重量（千克）')
            ->uSmallInt('sort')->comment('顺序，从大到小排列')
            ->string('image')->comment('图片')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('skus');
    }
}
