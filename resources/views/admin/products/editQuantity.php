<?php if ($product->isSingleSku()) : ?>
    <div class="form-group">
        <label class="col-xs-3 control-label">库存</label>
        <div class="col-xs-6">
            <input class="form-control sku-quantity" type="text" data-id="<?= $product->getFirstSku()->get('id') ?>" data-name="quantity" value="<?= $product->getFirstSku()->get('quantity') ?>">
        </div>
    </div>
<?php else : ?>
    <table class="record-table table table-bordered table-hover">
        <thead>
        <tr>
            <?php foreach ($product['skuConfigs'] as $skuConfig) : ?>
                <th><?= $skuConfig['name'] ?></th>
            <?php endforeach ?>
            <th>库存</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($product->getSkus() as $sku) : ?>
            <tr>
                <?php foreach ($sku->getSpecs() as $spec) : ?>
                    <td><?= $spec ?></td>
                <?php endforeach ?>
                <td>
                    <input type="text" class="sku-quantity text-center" data-id="<?= $sku['id'] ?>" data-name="quantity" value="<?= $sku['quantity'] ?>"/>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
