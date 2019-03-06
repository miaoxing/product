<ul class="product-tab-underline header-tab nav tab-underline <?= $req['picker'] ? 'border-y' : 'border-bottom' ?>">
  <li class="nav-item">
    <a class="nav-link js-product-drawer-toggle" data-dir="left" href="javascript:">
      <span class="caret caret-left"></span>
      分类
    </a>
  </li>
  <li class="nav-item <?= ($req['sort'] == '' || $req['sort'] == 'default') ? 'active border-primary' : '' ?>">
    <a class="nav-link text-active-primary" href="<?= $url->query('products', ['sort' => 'default']) ?>">默认</a>
  </li>
  <li class="nav-item <?= $req['sort'] == 'soldQuantity' ? 'active border-primary' : '' ?>">
    <a class="nav-link text-active-primary"
      href="<?= $url->query('products', [
        'sort' => 'soldQuantity',
        'order' => ($req['order'] == 'desc' ? 'asc' : 'desc'),
      ]) ?>">
      销量
      <?php if ($req['sort'] == 'soldQuantity') : ?>
        <i class="arrow <?= $req['order'] == 'desc' ? 'arrow-down' : 'arrow-up' ?>"></i>
      <?php endif ?>
    </a>
  </li>
  <li class="nav-item <?= $req['sort'] == 'price' ? 'active border-primary' : '' ?>">
    <a class="nav-link text-active-primary"
      href="<?= $url->query('products', ['sort' => 'price', 'order' => ($req['order'] == 'desc' ? 'asc' : 'desc')]) ?>">
      价格
      <?php if ($req['sort'] == 'price') : ?>
        <i class="arrow <?= $req['order'] == 'desc' ? 'arrow-down' : 'arrow-up' ?>"></i>
      <?php endif ?>
    </a>
  </li>
  <li class="nav-item">
    <a class="js-product-drawer-toggle nav-link" data-dir="right" href="javascript:">
      筛选 <?= $req['tags'] ? '<small>(已选)</small>' : '' ?>
      <span class="caret caret-right"></span>
    </a>
  </li>
</ul>

<?= $block->js() ?>
<script>
  require(['plugins/product/js/products', 'comps/snapjs/snap.min'], function (products) {
    products.indexAction();
  });
</script>
<?= $block->end() ?>
