<?php

namespace Miaoxing\Product\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20180122102617CreateCategoryDetailsTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('categoryDetails')
            ->id()
            ->int('appId')
            ->string('categoryId', 64)
            ->longtext('detailUp')
            ->longtext('detailDown')
            ->bool('showed')->defaults(true)
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('categoryDetails');
    }
}
