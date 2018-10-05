<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset([
  'plugins/product/css/admin/productsPicker.css',
]) ?>">
<?= $block->end() ?>

<script id="product-list-item-tpl" type="text/html">
  <li class="list-group-item">
    <%== template.render("product-tpl", product) %>
    <div class="media-actions">
      <a href="javascript:;" title="删除" class="light-grey remove-product">
        <i class="fa fa-times-circle-o"></i>
      </a>
    </div>
    <input type="hidden" name="productIds[]" value="<%= product.id %>">
  </li>
</script>

<?php require $this->getFile('@product/admin/products/richInfo.php') ?>
