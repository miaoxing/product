<?php

namespace Miaoxing\Product\Migration;

use Wei\Migration\BaseMigration;

class V20201124150002CreateSpecTables extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('product_images')->tableComment('商品图片；1:m')
            ->bigId()->comment('编号')
            ->uInt('app_id')->comment('应用编号')
            ->uBigInt('product_id')->comment('商品编号')
            ->uTinyInt('type')->defaults(1)->comment('图片类型，具体见模型常量')
            ->string('url')->comment('图片地址')
            ->string('description')->comment('图片说明')
            ->timestamps()
            ->userstamps()
            ->index(['product_id', 'app_id'])
            ->exec();

        $this->schema->table('specs')->tableComment('规格')
            ->bigId()->comment('编号')
            ->uInt('app_id')->comment('应用编号')
            ->string('name')->comment('规格名称')
            ->smallInt('sort')->comment('顺序，从大到小排列')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->index(['name', 'app_id'])
            ->exec();

        $this->schema->table('spec_values')->tableComment('规格的值；1:m')
            ->bigId()->comment('编号')
            ->uInt('app_id')->comment('应用编号')
            ->uBigInt('spec_id')->comment('规格编号')
            ->string('name')->comment('名称')
            ->smallInt('sort')->comment('顺序，从大到小排列')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->index(['spec_id', 'name', 'app_id'])
            ->exec();

        $this->schema->table('product_specs')->tableComment('商品规格；1:1')
            ->bigId()->comment('编号')
            ->uInt('app_id')->comment('应用编号')
            ->uBigInt('product_id')->comment('商品编号')
            ->json('specs')->comment('规格内容，内容如：[{id,values:[{id,name,image},...]},...]')
            ->timestamps()
            ->userstamps()
            ->index(['product_id', 'app_id'])
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('product_images')
            ->dropIfExists('specs')
            ->dropIfExists('spec_values')
            ->dropIfExists('product_specs');
    }
}
