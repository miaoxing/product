<?php

namespace Miaoxing\Product\Migration;

use Wei\Migration\BaseMigration;

class V20201016135613CreateCategoriesProductsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('categories_products')->tableComment('商品分类；m:n')
            ->bigId()->comment('编号')
            ->uBigInt('app_id')->comment('应用编号')
            ->uBigInt('category_id')->comment('分类编号')
            ->uBigInt('product_id')->comment('商品编号')
            ->uSmallInt('sort')->comment('顺序，从大到小排列')
            ->userstamps()
            ->timestamps()
            ->softDeletable()
            ->index(['product_id', 'category_id'])
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('categories_products');
    }
}
