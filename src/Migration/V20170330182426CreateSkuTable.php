<?php

namespace Miaoxing\Product\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20170330182426CreateSkuTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('sku')
            ->id()
            ->int('productId')
            ->decimal('price', 10)
            ->mediumInt('score')
            ->int('quantity')
            ->int('soldQuantity')
            ->string('no', 32)
            ->int('sort')
            ->string('attrIds', 128)->comment('SKU对应的多个属性的ID')
            ->timestampsV1()
            ->userstampsV1()
            ->softDeletableV1()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('sku');
    }
}
