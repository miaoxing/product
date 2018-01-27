<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset([
  'comps/select2/select2.css',
  'comps/select2-bootstrap-css/select2-bootstrap.css',
]) ?>">
<?= $block->end() ?>

<div class="form-group">
  <label class="col-lg-2 control-label" for="scope">
    活动商品
  </label>

  <div class="col-lg-4">
    <label class="radio-inline">
      <input type="radio" class="scope" name="scope" value="all" checked> 全部商品
    </label>
    <label class="radio-inline">
      <input type="radio" class="scope" name="scope" value="category"> 指定栏目
    </label>
    <label class="radio-inline">
      <input type="radio" class="scope" name="scope" value="product"> 指定商品
    </label>
  </div>
</div>

<div class="form-group scope-category-form-group scope-form-group">
  <label class="col-lg-2 control-label" for="category-ids">
    <span class="text-warning">*</span>
    选择栏目
  </label>

  <div class="col-lg-4">
    <select id="category-ids" name="categoryIds[]" class="form-control categoryIds" multiple>
    </select>
  </div>

  <label class="col-lg-6 help-text price-tips" for="price">
    最多选择10个栏目
  </label>
</div>

<div class="form-group scope-product-form-group scope-form-group">
  <label class="col-lg-2 control-label">
    选择商品
  </label>

  <div class="col-lg-4 products-picker">
    <input type="text" class="form-control product-typeahead" placeholder="请输入商品名称搜索">
  </div>

  <label class="col-lg-6 help-text">
    最多选择100个商品
  </label>
</div>

<?= $block->js() ?>
<script>
  require([
    'form',
    'template',
    'comps/select2/select2.min',
    'comps/typeahead.js/dist/typeahead.bundle.min'
  ], function (form) {
    var categoryJson = <?= json_encode(wei()->category()->notDeleted()->withParent('mall')->getTreeToArray()) ?>;
    form.toOptions($('#category-ids'), categoryJson, 'id', 'name');

    $('.scope').change(function(){
      $('.scope-form-group').hide();
      $('.scope-' + $(this).val() + '-form-group').show();
    });

    // 初始化商品选择器
    $.initChooseProduct = function (categoryIds) {
      $('.scope-form-group').find('.categoryIds').val(categoryIds).select2();
      $('.scope').filter(':checked').change();
    }
  });

  require(['plugins/product/js/admin/productsPicker'], function (ProductsPicker) {
    var productsPicker = new Object(ProductsPicker);
    productsPicker.init({
      $el: $('.products-picker'),
      products: <?= json_encode($products); ?>
    });
  });
</script>
<?= $block->end() ?>
<?php require $this->getFile('product:admin/products/productsPicker.php') ?>
